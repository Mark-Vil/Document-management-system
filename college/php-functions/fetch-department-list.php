<?php
include 'dbconnection.php';
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the college_code session variable is set
if (!isset($_SESSION['college_code'])) {
    echo json_encode([]);
    exit();
}

$college_code = $_SESSION['college_code'];

// Fetch data from the departments table based on the college_code
$sql = "SELECT d.department_name, d.department_code,
        (SELECT COUNT(*) FROM useraccount u WHERE u.department_code = d.department_code) +
        (SELECT COUNT(*) FROM archive a WHERE a.faculty_code = d.department_code) as connected_records
        FROM departments d 
        WHERE d.college_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $college_code);
$stmt->execute();
$result = $stmt->get_result();

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

$stmt->close();
$conn->close();

// Directly JSON encode the $departments array
echo json_encode($departments);
?>