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

// Get the user_id from the session
$user_id = $_SESSION['faculty_id'];

// Fetch the adviser_code from the useraccount table
$sql = "SELECT adviser_code FROM useraccount WHERE UserID = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($adviser_code);
    $stmt->fetch();
    $stmt->close();

    if (empty($adviser_code)) {
        echo json_encode(['status' => 'error', 'message' => 'No adviser code found']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

// Fetch pending submissions from the submission_status table
$sql = "
    SELECT ss.id, ss.submission_id, a.research_title, ss.dateofsubmission, ss.date_accepted, ss.status
    FROM submission_status ss
    JOIN archive a ON ss.submission_id = a.id
    WHERE ss.status IN ('Pending', 'Updated') AND ss.submission_code = ?
";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $adviser_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $submissions = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $row;
        }
    } else {
        $submissions = null;
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

$conn->close();
?>