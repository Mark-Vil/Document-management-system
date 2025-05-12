<?php
require 'dbconnection.php'; // Ensure this path is correct

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['student_id'];
$selected_year = isset($_POST['year']) ? intval($_POST['year']) : 0;

if ($selected_year > 0) {
    // Count accepted submissions for the selected year
    $query = "
        SELECT COUNT(*) as pending_count
        FROM submission_status ss
        JOIN archive a ON ss.submission_id = a.id
        JOIN useraccount u ON a.UserID = u.UserID
    WHERE (ss.status = 'Revise' OR ss.status = 'Updated' OR ss.status = 'Pending') AND YEAR(ss.dateofsubmission) = ? AND u.UserID = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $selected_year, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        echo json_encode(['status' => 'success', 'pending_count' => $data['pending_count']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid year selected']);
}

$conn->close();
?>