<?php
include 'dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];
    $researchID = $_POST['researchID'];
    $collegeCode = $_POST['collegeCode'];
    $departmentCode = $_POST['departmentCode'];

    // Check if user is logged in
    $currentUserID = null;
    if (isset($_SESSION['student_id'])) {
        $currentUserID = $_SESSION['student_id'];
    } else if (isset($_SESSION['faculty_id'])) {
        $currentUserID = $_SESSION['faculty_id'];
    }

    if (!$currentUserID) {
        echo json_encode(['status' => 'error', 'message' => 'No session found']);
        exit();
    }

    // Check if user owns the research
    $checkOwnerSql = "SELECT UserID FROM archive WHERE id = ?";
    if ($ownerStmt = $conn->prepare($checkOwnerSql)) {
        $ownerStmt->bind_param("i", $researchID);
        $ownerStmt->execute();
        $ownerStmt->bind_result($ownerID);
        $ownerStmt->fetch();
        $ownerStmt->close();

        if ($ownerID == $currentUserID) {
            echo json_encode(['status' => 'success', 'message' => 'Owner download not logged']);
            exit();
        }
    }

    // Check if the download already exists
    $checkSql = "SELECT COUNT(*) FROM downloads WHERE UserID = ? AND research_id = ?";
    if ($checkStmt = $conn->prepare($checkSql)) {
        $checkStmt->bind_param("ii", $userID, $researchID);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Download already logged']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare check statement']);
        exit();
    }

    // Insert the download into the downloads table
    $sql = "INSERT INTO downloads (UserID, research_id, college_code, department_code) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiss", $userID, $researchID, $collegeCode, $departmentCode);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to log download']);
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