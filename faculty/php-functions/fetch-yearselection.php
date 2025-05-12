<?php
require 'dbconnection.php'; // Ensure this path is correct

// Fetch distinct years from the dateofsubmission column in submission_status
$yearsQuery = "SELECT DISTINCT YEAR(dateofsubmission) as year FROM submission_status ORDER BY year DESC";
$result = $conn->query($yearsQuery);

$years = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Return data as JSON
echo json_encode($years);
?>