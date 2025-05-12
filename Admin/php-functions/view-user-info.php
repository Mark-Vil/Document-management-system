<?php
include 'dbconnection.php';

header('Content-Type: application/json');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id > 0) {
    // Fetch user information
    $sqlUserInfo = "
        SELECT 
            CONCAT(up.first_name, ' ', up.middle_name, ' ', up.last_name) AS full_name, 
            up.id_number, 
            ua.is_student, 
            ua.is_faculty,
            ua.status,
            IF(ua.is_student = 1, up.college, c.college_name) AS college,
            IF(ua.is_student = 1, up.department, d.department_name) AS department
        FROM userprofile up
        JOIN useraccount ua ON up.UserID = ua.UserID
        LEFT JOIN departments d ON ua.department_code = d.department_code
        LEFT JOIN colleges c ON d.college_code = c.college_code
        WHERE up.UserID = ?
    ";

    if ($stmtUserInfo = $conn->prepare($sqlUserInfo)) {
        $stmtUserInfo->bind_param('i', $user_id);
        $stmtUserInfo->execute();
        $resultUserInfo = $stmtUserInfo->get_result();
        $userInfo = $resultUserInfo->fetch_assoc();
        $stmtUserInfo->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare user info statement: ' . $conn->error]);
        exit();
    }

    // Fetch research data
    $sqlResearchData = "
        SELECT a.research_title, a.author, a.abstract, a.file_path, a.adviser_name
        FROM archive a
        WHERE a.UserID = ?
    ";

    if ($stmtResearchData = $conn->prepare($sqlResearchData)) {
        $stmtResearchData->bind_param('i', $user_id);
        $stmtResearchData->execute();
        $resultResearchData = $stmtResearchData->get_result();
        $researchData = $resultResearchData->fetch_all(MYSQLI_ASSOC);
        $stmtResearchData->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare research data statement: ' . $conn->error]);
        exit();
    }

    echo json_encode(['status' => 'success', 'userInfo' => $userInfo, 'researchData' => $researchData]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
}

$conn->close();
?>