<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submission_id = $_POST['submission_id'];

    $sql = "SELECT id, submission_id, research_title, abstract, author, co_authors, abstract, keywords, file_path, UserID, adviser_name, faculty_code, dateofsubmission FROM submission_history WHERE submission_id = ? ORDER BY id DESC";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $submission_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $history_data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        echo json_encode($history_data);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>