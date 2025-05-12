<?php
include 'dbconnection.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$student_id = $_SESSION['student_id'];

try {
    // Update notifications as viewed
    $sql = "UPDATE notifications SET is_viewed = 1 WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>