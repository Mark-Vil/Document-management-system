<?php
include 'dbconnection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_code = $_POST['department_code'];
    $year = $_POST['year'];

    // Prepare the SQL query
    $sql = "
        SELECT 
            a.id,
            a.research_title,
            a.author,
            a.file_path,
            ss.dateofsubmission,
            ss.date_accepted
        FROM 
            archive a
        JOIN 
            submission_status ss ON a.id = ss.submission_id
        WHERE 
            a.faculty_code = ?
            AND ss.status IN ('Accepted', 'Locked')
            AND YEAR(ss.dateofsubmission) = ?
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $department_code, $year);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        $response = ['error' => $e->getMessage()];
    }
} else {
    $response = ['error' => 'Invalid request method.'];
}

$conn->close();
echo json_encode($response);
?>