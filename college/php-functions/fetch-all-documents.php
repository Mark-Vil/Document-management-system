<?php
require '../../vendor/autoload.php';
require 'dbconnection.php'; // Ensure this path is correct

header('Content-Type: application/json');

if (isset($_GET['department_code']) && isset($_GET['year'])) {
    $departmentCode = $_GET['department_code'];
    $year = $_GET['year'];

    // Log the parameters for debugging
    error_log("Department code: " . $departmentCode);
    error_log("Year: " . $year);

    try {
        $query = "
            SELECT 
                a.id, 
                a.research_title AS title, 
                ua.email AS author_email, 
                ss.dateofsubmission, 
                ss.date_accepted, 
                ss.status 
            FROM 
                archive a
            JOIN 
                submission_status ss ON a.id = ss.submission_id
            JOIN 
                useraccount ua ON a.UserID = ua.UserID
            JOIN 
                departments d ON a.faculty_code = d.department_code
            WHERE 
                d.department_code = ? AND 
                YEAR(ss.dateofsubmission) = ?
        ";

        // Log the query for debugging
        error_log("Database query: " . $query);

        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $departmentCode, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $articles = $result->fetch_all(MYSQLI_ASSOC);
            // Log the fetched articles for debugging
            error_log("Fetched articles: " . print_r($articles, true));
        } else {
            $articles = [];
            error_log("Database query failed: " . $conn->error);
        }

        echo json_encode($articles);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No department code or year provided']);
}
?>