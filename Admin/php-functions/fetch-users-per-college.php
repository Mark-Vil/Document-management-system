<?php
include 'dbconnection.php';
session_start();

try {
    $sql = "
        SELECT c.college_name, COUNT(up.UserID) AS user_count
        FROM colleges c
        JOIN userprofile up ON c.college_code = up.college_code
        GROUP BY c.college_name
    ";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>