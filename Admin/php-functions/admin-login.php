<?php
include 'dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT admin_id, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashedPassword);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, create session
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_email'] = $email;

            $response = array("status" => "success", "message" => "Login successful.");
        } else {
            // Invalid password
            $response = array("status" => "error", "message" => "Invalid email or password.");
        }
    } else {
        // Email not found
        $response = array("status" => "error", "message" => "Invalid email or password.");
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
} else {
    $response = array("status" => "error", "message" => "Invalid request.");
    echo json_encode($response);
}
?>