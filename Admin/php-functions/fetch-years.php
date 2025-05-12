<?php
include 'dbconnection.php';

try {
    $sql = "SELECT DISTINCT YEAR(dateofsubmission) AS year 
            FROM submission_status 
            WHERE status IN ('Accepted', 'Locked') 
            ORDER BY year DESC";
    $result = $conn->query($sql);
    $years = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($years);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>