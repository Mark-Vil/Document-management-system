<?php
include 'dbconnection.php';
session_start(); // Start the session

function authenticateUser($email, $password) {
    global $conn;

    // Prepare and bind
    $stmt = $conn->prepare("SELECT UserID, email, password, is_student, is_faculty, status FROM useraccount WHERE email = ?");
    $stmt->bind_param("s", $email); // Corrected type specifier to 's' for string
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($UserID, $email, $hashedPassword, $is_student, $is_faculty, $status);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Check if the user is a faculty and the status is "Waiting", "Declined", or "Deactivated"
        if ($is_faculty && $status === "Declined") {
                $response = array(
                    "success" => false,
                    "message" => "This account has been declined."
                );
            } elseif (($is_student || $is_faculty) && $status === "Deactivated"){
                $response = array(
                    "success" => false,
                    "message" => "This account has been deactivated."
                );
            } else {
                // Set session variables
                $_SESSION['email'] = $email;
                if ($is_faculty) {
                    $_SESSION['role'] = 'Faculty';
                    $_SESSION['faculty_id'] = $UserID;
                    $redirectUrl = "faculty/logged-in.php";
                } elseif ($is_student) {
                    $_SESSION['role'] = 'Student';
                    $_SESSION['student_id'] = $UserID;
                    $redirectUrl = "logged-in.php";
                } else {
                    $_SESSION['role'] = 'Unknown';
                    $redirectUrl = "logged-in.php";
                }

                $response = array(
                    "success" => true,
                    "redirect_url" => $redirectUrl
                );
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "Incorrect email or password."
            );
        }
    } else {
        $response = array(
            "success" => false,
            "message" => "No account found."
        );
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
}

// Get POST data
$email = $_POST['email'];
$password = $_POST['password'];

// Call the function
authenticateUser($email, $password);
?>