<?php
include 'dbconnection.php';

$admin_email = 'admin@gmail.com'; // Replace with your admin email
$new_password = 'admin'; // Replace with desired password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed_password, $admin_email);

if ($stmt->execute()) {
    echo "Password reset successful. New password: " . $new_password;
} else {
    echo "Error resetting password: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>