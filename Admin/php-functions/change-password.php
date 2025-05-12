<?php
include 'dbconnection.php';


$email = $_POST['email'];
$currentPassword = $_POST['password'];
$newPassword = $_POST['newpassword'];
$confirmPassword = $_POST['confirmpassword'];

// Validate input
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(['status' => 'error', 'message' => 'New passwords do not match']);
    exit();
}

// Check current password
$sql = "SELECT password FROM admin WHERE email = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($currentPassword, $hashedPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

// Update password
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$sql = "UPDATE admin SET password = ? WHERE email = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $newHashedPassword, $email);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Password changed successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
}

$conn->close();
?>