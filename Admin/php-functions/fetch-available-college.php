<?php
include 'dbconnection.php';

$response = array();

$sql = "SELECT college_code, college_name FROM colleges WHERE status = 'No Account'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

$conn->close();
echo json_encode($response);
?>