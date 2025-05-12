<?php
include 'dbconnection.php';
session_start(); // Start the session

function createAccount($email, $password, $role, $department_code = null)
{
    global $conn;

    // Check if email is WMSU provided
    if (!preg_match('/@wmsu\.edu\.ph$/', $email)) {
        echo json_encode([
            "status" => "error", 
            "message" => "WMSU provided email address only."
        ]);
        return;
    }

    // Check if the email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM useraccount WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        // Email already exists
        $response = array("status" => "error", "message" => "Account with similar email existed");
        echo json_encode($response);
        $checkEmailStmt->close();
        return;
    }

    $checkEmailStmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Determine role columns
    $is_student = ($role === 'Student') ? 1 : 0;
    $is_faculty = ($role === 'Faculty') ? 1 : 0;

    // Set status to "Waiting" if the user is an adviser
    $status = $is_faculty ? "Waiting" : null;

    // Check if department_code exists if role is Adviser
    if ($is_faculty && $department_code) {
        $checkDeptStmt = $conn->prepare("SELECT department_code FROM departments WHERE department_code = ?");
        $checkDeptStmt->bind_param("i", $department_code);
        $checkDeptStmt->execute();
        $checkDeptStmt->store_result();

        if ($checkDeptStmt->num_rows == 0) {
            // Department code does not exist
            $response = array("status" => "error", "message" => "Invalid department code");
            echo json_encode($response);
            $checkDeptStmt->close();
            return;
        }

        $checkDeptStmt->close();
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO useraccount (email, password, is_student, is_faculty, department_code, status, is_emailverified) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $is_emailverified = 0; // Default value for is_emailverified
    $stmt->bind_param("ssisisi", $email, $hashedPassword, $is_student, $is_faculty, $department_code, $status, $is_emailverified);

    // Execute and check for errors
    if ($stmt->execute()) {
        $UserID = $stmt->insert_id;
        
        // Generate OTP and set timeout
        $otp = rand(100000, 999999);
        $otpStmt = $conn->prepare("UPDATE useraccount SET otp = ?, otp_timeout = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE UserID = ?");
        $otpStmt->bind_param("si", $otp, $UserID);
        $otpStmt->execute();
        
        // Send email
        require '../email/student-email.php';
        $subject = "Verify Your Email";
        $body = "Your verification code is: $otp\nVerify within 10 minutes or account will be deleted.";
        sendEmail($email, $subject, $body);

        // Simple success response
        $response = array(
            "status" => "success",
            "message" => "Account created! Check email for verification code."
        );
        
        
        echo json_encode($response);
    } else {
        $response = array("status" => "error", "message" => "Error: " . $stmt->error);
        echo json_encode($response);
    }

    $stmt->close();
    $conn->close();
}

// Get POST data
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];
$department_code = isset($_POST['departmentCode']) ? $_POST['departmentCode'] : null;

// Call the function
createAccount($email, $password, $role, $department_code);
?>