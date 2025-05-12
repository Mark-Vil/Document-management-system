<?php
include 'dbconnection.php';
session_start();

// Check if user is logged in and has permission
if (!isset($_SESSION['college_code'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Validate input
if (!isset($_POST['department_code'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$department_code = $_POST['department_code'];
$college_code = $_SESSION['college_code'];

$conn->begin_transaction();

try {
    // Delete department entry
    $sql = "DELETE FROM departments WHERE department_code = ? AND college_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $department_code, $college_code);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>