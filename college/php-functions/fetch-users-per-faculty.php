<?php
include 'dbconnection.php';
session_start();

if (!isset($_SESSION['college_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
    exit();
}

$college_code = $_SESSION['college_code'];

try {
    $sql = "
        SELECT d.department_name, COUNT(ua.UserID) AS user_count
        FROM departments d
        JOIN useraccount ua ON d.department_code = ua.department_code
        JOIN colleges c ON d.college_code = c.college_code
        WHERE c.college_code = ? AND ua.is_faculty = 1 AND ua.is_verified = 1
        GROUP BY d.department_name
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $college_code);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $data]);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>