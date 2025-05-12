<?php
include 'dbconnection.php';

$response = array();

if (isset($_POST['submission_id']) && isset($_POST['action'])) {
    $submission_id = $_POST['submission_id'];
    $action = $_POST['action'];

    // Determine the new status based on the action
    $new_status = ($action === 'lock') ? 'Locked' : 'Accepted';

    $sql = "UPDATE submission_status SET status = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $new_status, $submission_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response = ['status' => 'success', 'message' => 'Document ' . $action . 'ing successful.'];
            } else {
                $response = ['status' => 'error', 'message' => 'No rows affected. Please check the submission ID.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error];
        }
        $stmt->close();
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error];
    }
} else {
    $response = ['status' => 'error', 'message' => 'No submission ID or action provided'];
}

$conn->close();
echo json_encode($response);
?>