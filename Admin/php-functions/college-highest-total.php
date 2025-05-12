<?php
require 'dbconnection.php';

header('Content-Type: application/json');

try {
    $sql = "
        SELECT 
            c.college_name, 
            COUNT(a.id) as total_documents
        FROM 
            colleges c
            LEFT JOIN departments d ON c.college_code = d.college_code
            LEFT JOIN archive a ON d.department_code = a.faculty_code
        GROUP BY 
            c.college_name
        ORDER BY 
            total_documents DESC
    ";
    
    $stmt = $conn->prepare($sql);
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