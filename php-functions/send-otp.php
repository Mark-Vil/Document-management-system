<?php
require 'dbconnection.php';
require '../email/student-email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Generate OTP
    $otp = rand(100000, 999999);

    // Send OTP email
    $subject = 'Your OTP Code';
    $body = "
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
                    <p>Your verification code is <strong>$otp</strong>.</p>
                    <p>Best regards,<br>WMSU-RMIS Team</p>
                </div>
                <div class='email-footer'>
                    <p>This email was sent by WMSU-RMIS. For your OTP verification dont share this.</p>
                </div>
            </div>
        </body>
        </html>
    ";
    $emailResponse = sendEmail($email, $subject, $body);

    if ($emailResponse['status'] === 'success') {
        // Update OTP in the database
        $sql_update_otp = "UPDATE useraccount SET otp = ? WHERE email = ?";
        if ($stmt_update_otp = $conn->prepare($sql_update_otp)) {
            $stmt_update_otp->bind_param("is", $otp, $email);
            if ($stmt_update_otp->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update OTP in the database.']);
            }
            $stmt_update_otp->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare OTP update statement.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => $emailResponse['message']]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>