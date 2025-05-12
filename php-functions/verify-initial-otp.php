<?php
require 'dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Check OTP validity and timeout
    $stmt = $conn->prepare("SELECT UserID, is_faculty, is_student FROM useraccount 
                           WHERE email = ? AND otp = ? AND otp_timeout > NOW() 
                           AND is_emailverified = 0");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Update verification status
        $updateStmt = $conn->prepare("UPDATE useraccount 
                                    SET is_emailverified = 1, otp = NULL, otp_timeout = NULL 
                                    WHERE email = ?");
        $updateStmt->bind_param("s", $email);
        $updateStmt->execute();

        // Set session variables
        $_SESSION['email'] = $email;
        if ($user['is_faculty']) {
            $_SESSION['faculty_id'] = $user['UserID'];
            $_SESSION['role'] = 'Faculty';
            $redirect = 'faculty/dashboard.php';
        } else {
            $_SESSION['student_id'] = $user['UserID'];
            $_SESSION['role'] = 'Student';
            $redirect = 'logged-in.php';
        }

        echo json_encode([
            "status" => "success",
            "redirect_url" => $redirect
        ]);

    } else {
        // Check if timeout expired
        $timeoutStmt = $conn->prepare("SELECT UserID FROM useraccount 
                                     WHERE email = ? AND otp_timeout <= NOW() 
                                     AND is_emailverified = 0");
        $timeoutStmt->bind_param("s", $email);
        $timeoutStmt->execute();
        
        if ($timeoutStmt->get_result()->num_rows > 0) {
            // Delete expired unverified account
            $deleteStmt = $conn->prepare("DELETE FROM useraccount WHERE email = ?");
            $deleteStmt->bind_param("s", $email);
            $deleteStmt->execute();
            
            echo json_encode([
                "status" => "error",
                "message" => "Verification timeout expired. Account deleted."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid verification code."
            ]);
        }
    }
    $conn->close();
}
?>