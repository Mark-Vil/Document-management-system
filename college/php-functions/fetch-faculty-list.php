<?php
include 'dbconnection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the college_code session variable is set
if (!isset($_SESSION['college_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
    exit();
}

$college_code = $_SESSION['college_code'];

// Fetch data from the departments table based on the college_code
$sql = "SELECT department_name, department_code FROM departments WHERE college_code = ?";
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

echo json_encode($departments);
?>