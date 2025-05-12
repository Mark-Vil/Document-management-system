<?php
include 'dbconnection.php';
require '../../email/email.php';

if (isset($_POST['submission_id']) && isset($_POST['comments'])) {
    $submission_id = $_POST['submission_id'];
    $comments = $_POST['comments'];

    $sql = "UPDATE submission_status SET status = 'Revise', comments = ? WHERE submission_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $comments, $submission_id);
        if ($stmt->execute()) {
            // Fetch the UserID and research_title from the archive table
            $user_sql = "SELECT UserID, research_title FROM archive WHERE id = ?";
            if ($user_stmt = $conn->prepare($user_sql)) {
                $user_stmt->bind_param("i", $submission_id);
                $user_stmt->execute();
                $user_stmt->bind_result($user_id, $research_title);
                $user_stmt->fetch();
                $user_stmt->close();

                // Fetch the email from the useraccount table
                $email_sql = "SELECT email FROM useraccount WHERE UserID = ?";
                if ($email_stmt = $conn->prepare($email_sql)) {
                    $email_stmt->bind_param("i", $user_id);
                    $email_stmt->execute();
                    $email_stmt->bind_result($email);
                    $email_stmt->fetch();
                    $email_stmt->close();

                    // Insert notification
                    $title = "Submission Revision Requested";
                    $message = "Your adviser has requested a revision on your research submission titled \"$research_title\". Comments: $comments";
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
                    if ($email_response['status'] === 'success') {
                        echo json_encode(['status' => 'success', 'message' => 'Submission revision requested successfully. An email has been sent to the user.']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => "Submission revision requested, but email could not be sent. {$email_response['message']}"]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare email statement: ' . $conn->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare user statement: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to request submission revision.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No submission ID or comments provided']);
}

$conn->close();
?>