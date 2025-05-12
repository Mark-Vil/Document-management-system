<?php
include 'dbconnection.php';

// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    // Redirect to the login page if the session is not set
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['faculty_id'];

// SQL query to fetch data from submission_status and archive tables
$sql = "
    SELECT ss.submission_id, ss.id, ss.status, a.research_title, ss.dateofsubmission
    FROM submission_status ss
    JOIN archive a ON ss.submission_id = a.id
    WHERE a.UserID = ?
";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $submissions = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $row;
        }
    }

    echo json_encode($submissions);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

$conn->close();
?>