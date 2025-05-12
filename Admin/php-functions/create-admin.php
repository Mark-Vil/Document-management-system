<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM admin WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        // Email already exists
        $response = array("status" => "error", "message" => "Email already exists.");
        echo json_encode($response);
        $checkEmailStmt->close();
        return;
    }

    $checkEmailStmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);

    // Execute and check for errors
    if ($stmt->execute()) {
        $response = array("status" => "success", "message" => "Admin account created successfully.");
    } else {
        $response = array("status" => "error", "message" => "Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
} else {
    $response = array("status" => "error", "message" => "Invalid request.");
    echo json_encode($response);
}
?>