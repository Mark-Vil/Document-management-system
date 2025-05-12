<?php
include 'dbconnection.php';
session_start();

// Check if the college_code session variable is set
if (!isset($_SESSION['college_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
    exit();
}

$college_code = $_SESSION['college_code'];

try {
    // Fetch total documents per year
    $sql = "
        SELECT YEAR(ss.dateofsubmission) AS year, COUNT(a.id) AS total_documents
        FROM archive a
        LEFT JOIN departments d ON a.faculty_code = d.department_code
        LEFT JOIN submission_status ss ON a.id = ss.submission_id
        WHERE d.college_code = ?
        AND (ss.status = 'Accepted' OR ss.status = 'Locked')
        GROUP BY YEAR(ss.dateofsubmission)
        ORDER BY YEAR(ss.dateofsubmission)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $college_code);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>