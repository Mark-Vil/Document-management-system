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
$sql = "SELECT d.department_name, d.department_code, 
        COUNT(CASE WHEN s.status = 'Accepted' OR s.status = 'Locked' THEN a.id ELSE NULL END) AS total_archives
        FROM departments d
        LEFT JOIN archive a ON d.department_code = a.faculty_code
        LEFT JOIN submission_status s ON s.submission_id = a.id
        WHERE d.college_code = ?
        GROUP BY d.department_code
        ORDER BY total_archives DESC";

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