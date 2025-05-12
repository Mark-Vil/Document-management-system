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

$faculty_id = $_SESSION['faculty_id'];
$selected_year = isset($_POST['year']) ? intval($_POST['year']) : 0;

if ($selected_year > 0) {
    // Count pending submissions for the selected year where submission_code matches adviser_code
    $query = "
    SELECT COUNT(*) as pending_count
    FROM submission_status ss
    JOIN archive a ON ss.submission_id = a.id
    JOIN useraccount ua ON ss.submission_code = ua.adviser_code
    WHERE (ss.status = 'Pending' OR ss.status = 'Updated') AND YEAR(ss.dateofsubmission) = ? AND ua.UserID = ?
";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $selected_year, $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        // Debugging output
        $debug_info = [
            'query' => $query,
            'year' => $selected_year,
            'faculty_id' => $faculty_id,
            'pending_count' => $data['pending_count']
        ];
        error_log(json_encode($debug_info));

        echo json_encode(['status' => 'success', 'pending_count' => $data['pending_count']]);
    } else {
        $error_info = [
            'status' => 'error',
            'message' => 'Failed to prepare statement: ' . $conn->error
        ];
        error_log(json_encode($error_info));
        echo json_encode($error_info);
    }
} else {
    $error_info = [
        'status' => 'error',
        'message' => 'Invalid year selected'
    ];
    error_log(json_encode($error_info));
    echo json_encode($error_info);
}

$conn->close();
?>