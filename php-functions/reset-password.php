<?php
session_start();

include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new-password'];

    // Check if the email session variable is set
    if (isset($_SESSION['email-otp'])) {
        $email = $_SESSION['email-otp'];

        // Prepare the SQL query to fetch the user by email
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

            // Get the UserID
            $userId = $data['UserID'];

            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Prepare the SQL query to update the password in the database
            $stmt = $conn->prepare("UPDATE useraccount SET password = ? WHERE UserID = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);

            // Execute the query
            $stmt->execute();

            // Reset the session
            session_unset();

            echo json_encode(['status' => 'success', 'message' => 'Password reset successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email not found in session']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>