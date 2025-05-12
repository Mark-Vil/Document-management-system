<?php
include 'dbconnection.php';
session_start();

if (!isset($_SESSION['college_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
    exit();
}

$college_code = $_SESSION['college_code'];
$year = isset($_POST['year']) ? $_POST['year'] : null;

try {
    // Fetch the highest view count
    $sql = "
        SELECT research_id, COUNT(*) AS view_count
        FROM views
        WHERE college_code = ? AND YEAR(viewed_at) = ?
        GROUP BY research_id
        HAVING COUNT(*) > 0
        ORDER BY view_count DESC, MAX(viewed_at) DESC
        LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $college_code, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $highest_view = $result->fetch_assoc();
        $highest_view_count = $highest_view['view_count'];

        // Fetch all research_id with the highest view count, ordered by the latest viewed_at
        $sql = "
            SELECT a.research_title, a.file_path
            FROM archive a
            JOIN (
                SELECT research_id, MAX(viewed_at) AS latest_viewed_at
                FROM views
                WHERE college_code = ? AND YEAR(viewed_at) = ? 
                GROUP BY research_id
                HAVING COUNT(*) = ?
                ORDER BY latest_viewed_at DESC
            ) v ON a.id = v.research_id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $college_code, $year, $highest_view_count);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        $data = null;
    }

    $stmt->close();
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>