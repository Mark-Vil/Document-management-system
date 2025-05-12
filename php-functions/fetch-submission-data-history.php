<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submission_id = $_POST['id'];

    $sql = "SELECT id, research_title, author, co_authors, abstract, keywords, file_path, dateofsubmission, comments FROM submission_history WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $submission_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details_data = $result->fetch_assoc();
        $stmt->close();

        // Remove '../' from the file_path
        if (isset($details_data['file_path'])) {
            $details_data['file_path'] = str_replace('../', '', $details_data['file_path']);
        }

        echo json_encode($details_data);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>