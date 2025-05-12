<?php
require 'dbconnection.php';

header('Content-Type: application/json');

try {
    $year = intval($_POST['year']);

    $sql = "
        SELECT 
            a.id,
            a.research_title,
            ua.email AS author_email,
            ss.date_accepted,
            ss.dateofsubmission AS date_of_submission,
            ss.status
        FROM 
            archive a
        JOIN departments d ON a.faculty_code = d.department_code
        JOIN colleges c ON d.college_code = c.college_code
        JOIN useraccount ua ON a.UserID = ua.UserID
        JOIN submission_status ss ON a.id = ss.submission_id
        WHERE YEAR(ss.dateofsubmission) = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'] ?? '',
            'research_title' => $row['research_title'] ?? 'N/A',
            'author_email' => $row['author_email'] ?? 'N/A',
            'date_accepted' => $row['date_accepted'] ?? 'N/A',
            'date_of_submission' => $row['date_of_submission'] ?? 'N/A',
            'status' => $row['status'] ?? 'N/A'
        ];
    }

    $stmt->close();
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>