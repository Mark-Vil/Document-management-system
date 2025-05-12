<?php
include 'dbconnection.php';
session_start();

function decrypt($data) {
    return base64_decode($data);
}

$userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$archiveId = isset($_POST['archive_id']) ? decrypt($_POST['archive_id']) : '';

if ($userId && $archiveId) {
    try {
        // Step 1: Retrieve the faculty_code from the archive table
        $stmt = $conn->prepare("SELECT faculty_code FROM archive WHERE id = ?");
        $stmt->bind_param("i", $archiveId);
        $stmt->execute();
        $stmt->bind_result($facultyCode);
        $stmt->fetch();
        $stmt->close();

        if ($facultyCode) {
            // Step 2: Retrieve the department_code and college_code from the departments table
            $stmt = $conn->prepare("SELECT department_code, college_code FROM departments WHERE department_code = ?");
            $stmt->bind_param("s", $facultyCode);
            $stmt->execute();
            $stmt->bind_result($departmentCode, $collegeCode);
            $stmt->fetch();
            $stmt->close();

            if ($departmentCode && $collegeCode) {
                // Step 3: Insert the user_id, archive_id, department_code, college_code, and timestamp into the userinteractions table
                $currentTimestamp = date('Y-m-d H:i:s');
                $stmt = $conn->prepare("INSERT INTO userinteractions (UserID, research_id, department_code, college_code, time) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iisss", $userId, $archiveId, $departmentCode, $collegeCode, $currentTimestamp);
                $stmt->execute();
                $stmt->close();
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Department or College code not found']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Faculty code not found']);
        }
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}

$conn->close();
?>