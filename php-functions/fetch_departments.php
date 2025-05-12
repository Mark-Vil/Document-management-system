<?php
include 'dbconnection.php';

function fetch_departments() {
    global $conn;

    $sql = "
        SELECT d.department_code, d.department_name, c.college_name
        FROM departments d
        JOIN colleges c ON d.college_code = c.college_code
    ";
    $result = $conn->query($sql);

    $departments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }

    return $departments;
}
?>