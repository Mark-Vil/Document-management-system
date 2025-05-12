<?php
require 'dbconnection.php'; // Include your database connection
require '../../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentEmail = $_POST['currentEmail'];
    $newEmail = $_POST['newEmail'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update email in the database
        $sql_update_email = "UPDATE useraccount SET email = ?, is_emailverified = 0 WHERE email = ?";
        if ($stmt_update_email = $conn->prepare($sql_update_email)) {
            $stmt_update_email->bind_param("ss", $newEmail, $currentEmail);
            if (!$stmt_update_email->execute()) {
                throw new Exception('Failed to change email.');
            }
            $stmt_update_email->close();
        } else {
            throw new Exception('Failed to prepare email update statement.');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save OTP to the database
        $sql_update_otp = "UPDATE useraccount SET otp = ? WHERE email = ?";
        if ($stmt_update_otp = $conn->prepare($sql_update_otp)) {
            $stmt_update_otp->bind_param("is", $otp, $newEmail);
            if (!$stmt_update_otp->execute()) {
                throw new Exception('Failed to update OTP in the database.');
            }
            $stmt_update_otp->close();
        } else {
            throw new Exception('Failed to prepare OTP update statement.');
        }

        // Send OTP email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'wmsurmis@gmail.com'; // SMTP username
            $mail->Password = 'bmhw rmid ifpe helx'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('wmsurmis@gmail.com', 'WMSU-RMIS');
            $mail->addAddress($newEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = 'Your verification code is ' . $otp;

            $mail->send();
        } catch (Exception $e) {
            throw new Exception('Failed to send OTP email. Mailer Error: ' . $mail->ErrorInfo);
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'OTP sent to new email.']);
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>