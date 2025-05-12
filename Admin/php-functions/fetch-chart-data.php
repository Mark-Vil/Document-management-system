<?php
include 'dbconnection.php';

header('Content-Type: application/json');

if (isset($_GET['year'])) {
    $year = $_GET['year'];

    $query = "
        SELECT 
            c.college_name,
            COUNT(a.id) AS total_submissions
        FROM 
            colleges c
        LEFT JOIN 
            departments d ON c.college_code = d.college_code
        LEFT JOIN 
            archive a ON d.department_code = a.faculty_code
        LEFT JOIN 
            submission_status ss ON a.id = ss.submission_id
        WHERE 
            (ss.status IN ('Accepted', 'Locked') AND YEAR(ss.dateofsubmission) = ?)
            OR ss.submission_id IS NULL
        GROUP BY 
            c.college_name
        ORDER BY 
            c.college_name
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $year);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['error' => 'No year provided']);
}

$conn->close();
?>