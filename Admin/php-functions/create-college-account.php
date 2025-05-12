<?php
include 'dbconnection.php';
require '../../email/email.php';

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];
$collegeCode = $_POST['collegeCode'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare the response array
$response = array();

// Check if the college code exists in the colleges table
$stmt = $conn->prepare("SELECT college_code FROM colleges WHERE college_code = ?");
$stmt->bind_param("i", $collegeCode);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // College code exists, proceed with further checks
    $stmt->close();

    // Check if the college code already exists in the college_account table
    $stmt = $conn->prepare("SELECT college_code FROM college_account WHERE college_code = ?");
    $stmt->bind_param("i", $collegeCode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // College code already exists in the college_account table
        $response['status'] = 'error';
        $response['message'] = 'Error: College code already exists in the college_account table.';
    } else {
        // College code does not exist in the college_account table, proceed with insertion
        $stmt->close();

        // Insert the data into the college_account table with status 'Active'
        $status = 'Active';
        $stmt = $conn->prepare("INSERT INTO college_account (email, password, college_code, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $email, $hashedPassword, $collegeCode, $status);

        if ($stmt->execute()) {
            // Update the status in the colleges table to 'USED'
            $stmt->close();
            $stmt = $conn->prepare("UPDATE colleges SET status = 'USED' WHERE college_code = ?");
            $stmt->bind_param("i", $collegeCode);
            $stmt->execute();

            // Send email with account details
            $email_subject = 'Your College Admin Account Details';
            $email_body = "
                <html>
                <head>
                    <style>
                        .email-container {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            color: #333;
                        }
                        .email-header {
                            background-color: #f8f9fa;
                            padding: 10px;
                            border-bottom: 1px solid #dee2e6;
                        }
                        .email-body {
                            padding: 20px;
                        }
                        .email-footer {
                            background-color: #f8f9fa;
                            padding: 10px;
                            border-top: 1px solid #dee2e6;
                            font-size: 0.9em;
                            color: #6c757d;
                        }
                        .note {
                            color: #dc3545;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <h2>WMSU-RMIS</h2>
                        </div>
                        <div class='email-body'>
                            <p>Dear User,</p>
                            <p>Your college admin account has been created successfully. Below are your account details:</p>
                            <p><strong>Email:</strong> $email</p>
                            <p><strong>Password:</strong> $password</p>
                            <p class='note'>Note: Please do not share this email and password with others.</p>
                            <p>Best regards,<br>WMSU-RMIS Team</p>
                        </div>
                        <div class='email-footer'>
                            <p>This email was sent by WMSU-RMIS. If you did not request this account, please contact us immediately.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            $email_response = sendEmail($email, $email_subject, $email_body);
            if ($email_response['status'] === 'success') {
                $response['status'] = 'success';
                $response['message'] = 'Account created successfully. An email has been sent to the user email.';
            } else {
                $response['status'] = 'error';
                $response['message'] = "Account created, but email could not be sent. {$email_response['message']}";
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $stmt->error;
        }
    }
} else {
    // College code does not exist
    $response['status'] = 'error';
    $response['message'] = 'Invalid college code';
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>