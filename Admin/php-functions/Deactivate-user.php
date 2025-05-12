<?php
include 'dbconnection.php';

header('Content-Type: application/json');

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

if ($user_id > 0) {
    $sql = "UPDATE useraccount SET status = 'Deactivated' WHERE UserID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User deactivated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to deactivate user.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
}

$conn->close();
?>