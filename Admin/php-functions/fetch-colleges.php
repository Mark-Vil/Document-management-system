<?php
include 'dbconnection.php';

$response = array();

try {
    // Fetch all available colleges
    $sql_colleges = "SELECT college_code, college_name FROM colleges ORDER BY college_name";
    $result_colleges = $conn->query($sql_colleges);
    $colleges = array();
    while ($row = $result_colleges->fetch_assoc()) {
        $colleges[] = $row;
    }
    $response = ['colleges' => $colleges];
} catch (mysqli_sql_exception $e) {
    $response = ['error' => $e->getMessage()];
}

$conn->close();
echo json_encode($response);
?>