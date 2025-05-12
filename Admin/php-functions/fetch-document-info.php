<?php
include 'dbconnection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Prepare the SQL query
    $sql = "
        SELECT 
            a.research_title,
            a.author,
            a.co_authors,
            a.abstract,
            a.adviser_name,
            ss.dateofsubmission,
            ss.date_accepted,
            ss.status
        FROM 
            archive a
        JOIN 
            submission_status ss ON a.id = ss.submission_id
        WHERE 
            a.id = ?
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = $result->fetch_assoc();
    } catch (mysqli_sql_exception $e) {
        $response = ['error' => $e->getMessage()];
    }
} else {
    $response = ['error' => 'Invalid request method.'];
}

$conn->close();
echo json_encode($response);
?>