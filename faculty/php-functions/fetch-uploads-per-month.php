<?php
require 'dbconnection.php'; // Ensure this path is correct

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['faculty_id'];
$selected_year = isset($_POST['year']) ? intval($_POST['year']) : 0;

if ($selected_year > 0) {
    // Fetch uploads per month for the selected year
    $query = "
        SELECT MONTH(ss.dateofsubmission) as month, COUNT(*) as upload_count
        FROM submission_status ss
        JOIN archive a ON ss.submission_id = a.id
        WHERE YEAR(ss.dateofsubmission) = ? AND a.UserID = ?
        GROUP BY MONTH(ss.dateofsubmission)
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $selected_year, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $uploads_per_month = array_fill(1, 12, 0); // Initialize array with 12 months

        while ($row = $result->fetch_assoc()) {
            $uploads_per_month[intval($row['month'])] = intval($row['upload_count']);
        }

        $stmt->close();
        echo json_encode(['status' => 'success', 'data' => $uploads_per_month]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid year selected']);
}

$conn->close();
?>