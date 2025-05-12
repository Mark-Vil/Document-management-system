<?php
include 'dbconnection.php';
session_start();

// Check if the college_code session variable is set
if (!isset($_SESSION['college_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
    exit();
}

$college_code = $_SESSION['college_code'];

$sql = "
    SELECT 
        YEAR(ss.dateofsubmission) AS year, 
        COUNT(a.id) AS total_paper,
        d.department_name
    FROM 
        departments d
    LEFT JOIN 
        archive a 
    ON 
        d.department_code = a.faculty_code 
    LEFT JOIN 
        submission_status ss 
    ON 
        a.id = ss.submission_id 
    WHERE 
        d.college_code = ?
        AND (ss.status = 'Accepted' OR ss.status = 'Locked')
";

$params = [$college_code];
$types = "s";

if (isset($_POST['department_code']) && !empty($_POST['department_code'])) {
    $department_code = $_POST['department_code'];
    $sql .= " AND d.department_code = ?";
    $params[] = $department_code;
    $types .= "s";
} else {
    // If no department is selected, find the department with the highest total_paper
    $sql .= " AND d.department_code = (
        SELECT d2.department_code
        FROM departments d2
        LEFT JOIN archive a2 ON d2.department_code = a2.faculty_code
        LEFT JOIN submission_status ss2 ON a2.id = ss2.submission_id
        WHERE d2.college_code = ?
        AND (ss2.status = 'Accepted' OR ss2.status = 'Locked')
        GROUP BY d2.department_code
        ORDER BY COUNT(a2.id) DESC
        LIMIT 1
    )";
    $params[] = $college_code;
    $types .= "s";
}

if (isset($_POST['year']) && !empty($_POST['year'])) {
    $year = $_POST['year'];
    $sql .= " AND YEAR(ss.dateofsubmission) = ?";
    $params[] = $year;
    $types .= "i";
}

$sql .= " GROUP BY YEAR(ss.dateofsubmission), d.department_name";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    echo json_encode($data);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
}

$conn->close();
?>