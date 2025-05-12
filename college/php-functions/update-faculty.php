<?php
include 'dbconnection.php';
session_start();

if (!isset($_SESSION['college_code'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['original_department_code']) || 
    !isset($_POST['department_name']) || 
    !isset($_POST['department_code'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$original_code = $_POST['original_department_code'];
$new_name = $_POST['department_name'];
$new_code = $_POST['department_code'];
$college_code = $_SESSION['college_code'];

$conn->begin_transaction();

try {
    // 1. First create the new department entry
    $sql1 = "INSERT INTO departments (department_name, department_code, college_code) 
             VALUES (?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("sss", $new_name, $new_code, $college_code);
    $stmt1->execute();

    // 2. Update archive table references
    $sql2 = "UPDATE archive 
             SET faculty_code = ? 
             WHERE faculty_code = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ss", $new_code, $original_code);
    $stmt2->execute();

    // 3. Update useraccount table references
    $sql3 = "UPDATE useraccount 
             SET department_code = ? 
             WHERE department_code = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("ss", $new_code, $original_code);
    $stmt3->execute();

    // 4. Delete old department entry
    $sql4 = "DELETE FROM departments 
             WHERE department_code = ? AND college_code = ?";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("ss", $original_code, $college_code);
    $stmt4->execute();

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} finally {
    if (isset($stmt1)) $stmt1->close();
    if (isset($stmt2)) $stmt2->close();
    if (isset($stmt3)) $stmt3->close();
    if (isset($stmt4)) $stmt4->close();
    $conn->close();
}
?>