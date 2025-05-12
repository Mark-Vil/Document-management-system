<?php
require 'dbconnection.php';
require '../email/student-email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    $conn->begin_transaction();

    try {
        // Check if email already exists in verified accounts
        $checkStmt = $conn->prepare("SELECT email FROM useraccount WHERE email = ? AND is_emailverified = 1");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            throw new Exception("Email already registered");
        }

        // Delete any existing unverified entries
        $deleteStmt = $conn->prepare("DELETE FROM useraccount WHERE email = ? AND is_emailverified = 0");
        $deleteStmt->bind_param("s", $email);
        $deleteStmt->execute();

        // Insert new unverified entry
        $insertStmt = $conn->prepare("INSERT INTO useraccount (email, otp, is_emailverified) VALUES (?, ?, 0)");
        $insertStmt->bind_param("ss", $email, $otp);
        $insertStmt->execute();

        // Send email
        $emailResult = sendEmail($email, "Email Verification", "Your OTP is: $otp");
        
        if ($emailResult['status'] !== 'success') {
            throw new Exception("Failed to send email");
        }

        $conn->commit();
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
}
?>