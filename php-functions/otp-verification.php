<?php
session_start();

header('Content-Type: application/json');

include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'];

    // Check if the email is stored in the session
    if (isset($_SESSION['email-otp'])) {
        $email = $_SESSION['email-otp'];

        // Prepare the SQL query to fetch the OTP from the database
        $stmt = $conn->prepare("SELECT otp FROM useraccount WHERE email = ?");
        $stmt->bind_param("s", $email);

        // Execute the query
        $stmt->execute();

        // Get the result
        $stmt->bind_result($storedOtp);
        $stmt->fetch();

        // Check if the OTP matches
        if ($otp == $storedOtp) {
            // OTP is correct, create a new session for reset-password form
            $_SESSION['verified'] = true;
            echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email not found in session']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>