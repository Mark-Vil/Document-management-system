<?php
include 'dbconnection.php';

// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if session variables are set
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['faculty_id'];

// First get the adviser_code from the faculty's useraccount
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
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
    exit();
}

// Fetch student data with matching adviser_code
$sql = "
    SELECT 
        ua.email,
        ua.UserID,
        up.id_number,
        ua.status,
        up.first_name,
        up.middle_name,
        up.last_name,
        up.cor
    FROM useraccount ua
    JOIN userprofile up ON ua.UserID = up.UserID
    WHERE up.advisor_code = ? AND ua.status != 'Waiting'
";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $adviser_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    } else {
        $students = null;
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
    exit();
}

$conn->close();
echo json_encode($students);
?>