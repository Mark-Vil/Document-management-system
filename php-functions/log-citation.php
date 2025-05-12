<?php
include 'dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];
    $researchID = $_POST['researchID'];
    $collegeCode = $_POST['collegeCode'];
    $departmentCode = $_POST['departmentCode'];

    // Check if the download already exists
    $checkSql = "SELECT COUNT(*) FROM citation WHERE UserID = ? AND research_id = ?";
    if ($checkStmt = $conn->prepare($checkSql)) {
        $checkStmt->bind_param("ii", $userID, $researchID);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Citation already logged']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare check statement']);
        exit();
    }

    // Insert the download into the citation table
    $sql = "INSERT INTO citation (UserID, research_id, college_code, department_code) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiss", $userID, $researchID, $collegeCode, $departmentCode);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to log citation']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>