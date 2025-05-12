<?php
include 'dbconnection.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = $_POST['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Fetch file paths from archive table
        $query = "SELECT file_path FROM archive WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $documentId);
        $stmt->execute();
        $stmt->bind_result($archiveFilePath);
        $stmt->fetch();
        $stmt->close();

        // Fetch file paths from submission_history table
        $query = "SELECT file_path FROM submission_history WHERE submission_id IN (SELECT id FROM submission_status WHERE submission_id = ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $documentId);
        $stmt->execute();
        $stmt->bind_result($historyFilePath);
        $submissionHistoryFilePaths = [];
        while ($stmt->fetch()) {
            $submissionHistoryFilePaths[] = $historyFilePath;
        }
        $stmt->close();

        // Delete files from filesystem
        if ($archiveFilePath) {
            $archiveFilePath = realpath(__DIR__ . '/../../' . str_replace('../', '', $archiveFilePath));
            if ($archiveFilePath && file_exists($archiveFilePath)) {
                unlink($archiveFilePath);
            }
        }

        foreach ($submissionHistoryFilePaths as $filePath) {
            $filePath = realpath(__DIR__ . '/../../' . str_replace('../', '', $filePath));
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete from views table
        $query = "DELETE FROM views WHERE research_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $documentId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from downloads table
        $query = "DELETE FROM downloads WHERE research_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $documentId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from citation table
        $query = "DELETE FROM citation WHERE research_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $documentId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from submission_history table
        $query = "DELETE FROM submission_history WHERE submission_id IN (SELECT id FROM submission_status WHERE submission_id = ?)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $documentId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from submission_status table
        $query = "DELETE FROM submission_status WHERE submission_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $documentId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from archive table
        $query = "DELETE FROM archive WHERE id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $documentId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Commit the transaction
        $conn->commit();
        $response['success'] = true;
    } catch (Exception $e) {
        // Rollback the transaction
        $conn->rollback();
        $response['success'] = false;
        $response['error'] = $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['error'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
?>