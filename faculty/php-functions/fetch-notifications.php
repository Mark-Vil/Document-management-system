<?php
include 'dbconnection.php';
session_start();

if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$student_id = $_SESSION['faculty_id'];

try {
    // Fetch notifications
    $sql = "SELECT UserID, title, message, created_at, is_viewed FROM notifications WHERE UserID = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    $unseen_count = 0;
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
        if ($row['is_viewed'] == 0) {
            $unseen_count++;
        }
    }

    echo json_encode(['status' => 'success', 'notifications' => $notifications, 'unseen_count' => $unseen_count]);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>