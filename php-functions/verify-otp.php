<?php
require 'dbconnection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otpCode = $_POST['otpCode'];

    // Verify OTP
    $sql_verify_otp = "SELECT otp FROM useraccount WHERE email = ? AND otp = ?";
    if ($stmt_verify_otp = $conn->prepare($sql_verify_otp)) {
        $stmt_verify_otp->bind_param("si", $email, $otpCode);
        $stmt_verify_otp->execute();
        $stmt_verify_otp->store_result();

        if ($stmt_verify_otp->num_rows > 0) {
            // OTP is verified, update is_emailverified to 1
            $sql_update_verified = "UPDATE useraccount SET is_emailverified = 1 WHERE email = ?";
            if ($stmt_update_verified = $conn->prepare($sql_update_verified)) {
                $stmt_update_verified->bind_param("s", $email);
                if ($stmt_update_verified->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'OTP verified and email marked as verified.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update email verification status.']);
                }
                $stmt_update_verified->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare email verification update statement.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP code.']);
        }

        $stmt_verify_otp->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare OTP verification statement.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>