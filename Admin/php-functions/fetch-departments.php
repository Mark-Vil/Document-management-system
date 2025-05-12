<?php
include 'dbconnection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $college_code = $_POST['college_code'];
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    // Prepare the SQL query
    $sql = "
        SELECT 
            c.college_name,
            d.department_name,
            d.department_code,
            COUNT(a.id) AS total_documents
        FROM 
            departments d
        JOIN 
            colleges c ON d.college_code = c.college_code
        LEFT JOIN 
            archive a ON d.department_code = a.faculty_code
        LEFT JOIN 
            submission_status ss ON a.id = ss.submission_id
        WHERE 
            d.college_code = ?
            AND ss.status IN ('Accepted', 'Locked')
            AND (? = '' OR YEAR(ss.dateofsubmission) = ?)
        GROUP BY 
            c.college_name, d.department_name, d.department_code
        ORDER BY 
            total_documents DESC, c.college_name, d.department_name
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $college_code, $year, $year);
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