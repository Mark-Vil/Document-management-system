<?php
require '../../vendor/autoload.php';
require '../../email/email.php'; // Include the email sending script

include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $action = $_POST['action'];

    // Determine the new status based on the action
    $new_status = ($action === 'approve') ? 'Active' : 'Rejected';
    $is_verified = ($action === 'approve') ? 1 : 0;

    // Fetch the UserID from useraccount table based on email
    $sql_fetch_user = "SELECT UserID FROM useraccount WHERE email = ?";
    if ($stmt_fetch_user = $conn->prepare($sql_fetch_user)) {
        $stmt_fetch_user->bind_param("s", $email);
        $stmt_fetch_user->execute();
        $stmt_fetch_user->bind_result($user_id);
        $stmt_fetch_user->fetch();
        $stmt_fetch_user->close();

        // If action is reject, fetch the file path from userprofile table and delete the file
        if ($action === 'reject') {
            $sql_fetch_file = "SELECT cor FROM userprofile WHERE UserID = ?";
            if ($stmt_fetch_file = $conn->prepare($sql_fetch_file)) {
                $stmt_fetch_file->bind_param("i", $user_id);
                $stmt_fetch_file->execute();
                $stmt_fetch_file->bind_result($file_path);
                $stmt_fetch_file->fetch();
                $stmt_fetch_file->close();

                // Adjust the file path to account for the script's location
                $full_path = realpath(__DIR__ . '/../../' . $file_path);

                // Delete the file if it exists
                if ($full_path && file_exists($full_path)) {
                    unlink($full_path);
                }
            }
        }

        // Update the status and is_verified in the useraccount table based on email
        $sql_update_status = "UPDATE useraccount SET status = ?, is_verified = ? WHERE email = ?";
        if ($stmt_update_status = $conn->prepare($sql_update_status)) {
            $stmt_update_status->bind_param("sis", $new_status, $is_verified, $email);
            if ($stmt_update_status->execute()) {
                // Insert notification
                $title = ($action === 'approve') ? 'Account Approved!' : 'Account Rejected';
                $message = ($action === 'approve') ? 'Your account has been approved by your adviser.' : 'Your account has been rejected by your adviser.';
                $notification_sql = "INSERT INTO notifications (UserID, title, message) VALUES (?, ?, ?)";
                if ($notification_stmt = $conn->prepare($notification_sql)) {
                    $notification_stmt->bind_param("iss", $user_id, $title, $message);
                    $notification_stmt->execute();
                    $notification_stmt->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare notification statement: ' . $conn->error]);
                    exit();
                }

                // Send email notification
                $email_subject = $title;
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
                                <p>$message</p>
                                <p>Best regards,<br>WMSU-RMIS Team</p>
                            </div>
                            <div class='email-footer'>
                                <p>This email was sent by WMSU-RMIS. If you did not request this notification, please contact us immediately.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";
                $email_response = sendEmail($email, $email_subject, $email_body);

                echo json_encode(['status' => 'success', 'message' => 'User status updated successfully.', 'email_response' => $email_response]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update user status.']);
            }
            $stmt_update_status->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch user ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>