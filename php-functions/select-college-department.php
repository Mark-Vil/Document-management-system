<?php
require 'dbconnection.php'; // Ensure this path is correct

if (isset($_GET['college_code'])) {
    $collegeCode = $_GET['college_code'];

    // Fetch departments based on the selected college
    $departmentsQuery = "SELECT department_name FROM departments WHERE college_code = ?";
    $stmt = $conn->prepare($departmentsQuery);
    $stmt->bind_param("s", $collegeCode);
    $stmt->execute();
    $result = $stmt->get_result();

    $departments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }

    // Return data as JSON
    echo json_encode($departments);
}
?>