<?php
require 'dbconnection.php';
session_start();

$userId = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : 
         (isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : '');

$response = ['status' => 'error', 'message' => 'No data found'];

if ($userId) {
    $sql = "SELECT 
                a.id,
                a.research_title,
                a.author,
                a.co_authors,
                a.abstract,
                ss.date_accepted,
                c.college_name
            FROM userinteractions ui
            JOIN archive a ON ui.research_id = a.id
            JOIN submission_status ss ON a.id = ss.submission_id
            JOIN departments d ON a.faculty_code = d.department_code
            JOIN colleges c ON d.college_code = c.college_code
            WHERE ui.UserID = ?
            GROUP BY a.id
            ORDER BY MAX(ui.time) DESC
            LIMIT 2";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $archives = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($archives as &$archive) {
            $archive['encrypted_id'] = base64_encode($archive['id']);
        }
        $response = [
            'status' => 'success',
            'recentArchives' => $archives
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>