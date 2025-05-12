<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest' || !isset($_POST['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

// Include your database connection file and the email function
include 'dbconnection.php';
require '../email/email.php'; // Include the email function

// Get the email from the POST data
$email = $_POST['email'];

// Prepare the SQL query
$stmt = $conn->prepare("SELECT * FROM useraccount WHERE email = ?");
$stmt->bind_param("s", $email);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the email exists in the database
if ($result->num_rows > 0) {
    // Fetch the data
    $data = $result->fetch_assoc();

    // Get the user ID
    $userId = $data['UserID'];

    // Generate a random OTP
    $otp = rand(100000, 999999);

    // Prepare the SQL query to update the OTP in the database
    $stmt = $conn->prepare("UPDATE useraccount SET otp = ? WHERE UserID = ?");
    $stmt->bind_param("is", $otp, $userId);

    // Execute the query
    $stmt->execute();

    // Create the email subject and body
    $subject = 'Password Reset OTP For Your WMSU-RMIS Account';
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
                    <p>Your OTP for password reset is <strong>$otp</strong>.</p>
                    <p>Best regards,<br>WMSU-RMIS Team</p>
                </div>
                <div class='email-footer'>
                    <p>This email was sent by WMSU-RMIS. Don't share this.</p>
                </div>
            </div>
        </body>
        </html>
    ";

    // Send the email
    $emailResponse = sendEmail($email, $subject, $body);

    if ($emailResponse['status'] === 'success') {
        // Store the email in the session
        $_SESSION['email-otp'] = $email;
        echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error sending OTP: ' . $emailResponse['message']]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Email not found']);
}

$stmt->close();
$conn->close();
?>