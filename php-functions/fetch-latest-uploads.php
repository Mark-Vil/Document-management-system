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
    // Fetch latest uploads for the selected year
    $query = "
        SELECT a.research_title, REPLACE(a.file_path, '../', '') AS file_path
        FROM archive a
        JOIN submission_status ss ON ss.submission_id = a.id
        WHERE YEAR(ss.dateofsubmission) = ? AND a.UserID = ?
        ORDER BY ss.dateofsubmission DESC
        LIMIT 5
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $selected_year, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $uploads = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        echo json_encode(['status' => 'success', 'data' => $uploads]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid year selected']);
}

$conn->close();
?>