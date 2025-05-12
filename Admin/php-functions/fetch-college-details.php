<?php
require 'dbconnection.php'; // Ensure this path is correct

if (isset($_GET['college_code'])) {
    $collegeCode = $_GET['college_code'];

    // Fetch college details
    $collegeQuery = "
        SELECT 
            ca.email, 
            ca.status, 
            c.college_name 
        FROM 
            college_account ca
        JOIN 
            colleges c ON ca.college_code = c.college_code
        WHERE 
            ca.college_code = ?
    ";
    $stmt = $conn->prepare($collegeQuery);
    $stmt->bind_param("s", $collegeCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $collegeDetails = $result->fetch_assoc();

    // Fetch departments
    $departmentsQuery = "
        SELECT 
            department_name 
        FROM 
            departments 
        WHERE 
            college_code = ?
    ";
    $stmt = $conn->prepare($departmentsQuery);
    $stmt->bind_param("s", $collegeCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $departments = [];
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }

    // Combine data
    $data = [
        'college_name' => $collegeDetails['college_name'],
        'email' => $collegeDetails['email'],
        'status' => $collegeDetails['status'],
        'departments' => $departments
    ];

    // Return data as JSON
    echo json_encode($data);
}
?>