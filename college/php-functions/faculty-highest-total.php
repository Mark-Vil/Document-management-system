<?php
require 'dbconnection.php';

header('Content-Type: application/json');

try {
    // Get the year parameter from the request
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    // Get the college_code from the session (assuming it's stored in the session)
    session_start();
    if (!isset($_SESSION['college_code'])) {
        throw new Exception('College code not found in session.');
    }
    $college_code = $_SESSION['college_code'];

    $sql = "
        SELECT 
            d.department_name AS faculty_name, 
            COUNT(CASE WHEN YEAR(ss.dateofsubmission) = ? THEN a.id ELSE NULL END) as total_documents
        FROM 
            departments d
            LEFT JOIN archive a ON d.department_code = a.faculty_code
            LEFT JOIN submission_status ss ON a.id = ss.submission_id
        WHERE 
            d.college_code = ?
        GROUP BY 
            d.department_name
        ORDER BY 
            total_documents DESC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $year, $college_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $highestTotals = [];
    while ($row = $result->fetch_assoc()) {
        $highestTotals[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $highestTotals
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>