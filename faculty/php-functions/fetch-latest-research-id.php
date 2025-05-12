<?php
require 'dbconnection.php';

session_start();
$userId = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : (isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : '');

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    $sql = "
        SELECT research_id
        FROM userinteractions
        WHERE UserID = ?
        ORDER BY time DESC
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($researchId);
    $stmt->fetch();
    $stmt->close();

    if ($researchId) {
        echo json_encode(['success' => true, 'research_id' => $researchId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No interactions found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>