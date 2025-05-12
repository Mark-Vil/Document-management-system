<?php
include 'dbconnection.php';
session_start();

$year = isset($_POST['year']) ? $_POST['year'] : null;

try {
    // Fetch the most viewed college
    $sql = "
        SELECT c.college_name, v.college_code, v.download_count
        FROM colleges c
        JOIN (
            SELECT college_code, COUNT(*) AS download_count
            FROM downloads
            WHERE YEAR(downloaded_at) = ?
            GROUP BY college_code
            ORDER BY download_count DESC
            LIMIT 1
        ) v ON c.college_code = v.college_code
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_assoc();

    $stmt->close();
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>