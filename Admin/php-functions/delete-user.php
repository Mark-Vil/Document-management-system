<?php
include 'dbconnection.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Fetch file paths from archive table
        $query = "SELECT file_path, id FROM archive WHERE UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($archiveFilePath, $researchId);
        $archiveFilePaths = [];
        $researchIds = [];
        while ($stmt->fetch()) {
            $archiveFilePaths[] = $archiveFilePath;
            $researchIds[] = $researchId;
        }
        $stmt->close();

        // Fetch file paths from submission_history table
        $query = "SELECT file_path FROM submission_history WHERE submission_id IN (SELECT id FROM submission_status WHERE submission_id IN (SELECT id FROM archive WHERE UserID = ?))";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($historyFilePath);
        $submissionHistoryFilePaths = [];
        while ($stmt->fetch()) {
            $submissionHistoryFilePaths[] = $historyFilePath;
        }
        $stmt->close();

        // Fetch profile path and user type from useraccount table
        $query = "SELECT up.profile_path, ua.is_faculty, ua.is_student FROM userprofile up JOIN useraccount ua ON up.UserID = ua.UserID WHERE up.UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($profilePath, $isFaculty, $isStudent);
        $stmt->fetch();
        $stmt->close();

        // Delete files from filesystem
        foreach ($archiveFilePaths as $filePath) {
            $filePath = realpath(__DIR__ . '/../../' . str_replace('../', '', $filePath));
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
        }

        foreach ($submissionHistoryFilePaths as $filePath) {
            $filePath = realpath(__DIR__ . '/../../' . str_replace('../', '', $filePath));
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete profile path file
        if ($profilePath) {
            if ($isStudent == 1) {
                $profilePath = realpath(__DIR__ . '/../' . $profilePath);
            } else if ($isFaculty == 1) {
                $profilePath = realpath(__DIR__ . '/../../' . str_replace('../', '', $profilePath));
            }
            if ($profilePath && file_exists($profilePath)) {
                unlink($profilePath);
            }
        }

        // Check if there are any research IDs before executing the queries
        if (!empty($researchIds)) {
            // Delete from views table
            $query = "DELETE FROM views WHERE research_id IN (" . implode(',', array_fill(0, count($researchIds), '?')) . ")";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param(str_repeat('i', count($researchIds)), ...$researchIds);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to execute query: ' . $stmt->error);
                }
                $stmt->close();
            } else {
                throw new Exception('Failed to prepare statement: ' . $conn->error);
            }

            // Delete from downloads table
            $query = "DELETE FROM downloads WHERE research_id IN (" . implode(',', array_fill(0, count($researchIds), '?')) . ")";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param(str_repeat('i', count($researchIds)), ...$researchIds);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to execute query: ' . $stmt->error);
                }
                $stmt->close();
            } else {
                throw new Exception('Failed to prepare statement: ' . $conn->error);
            }

            // Delete from citation table
            $query = "DELETE FROM citation WHERE research_id IN (" . implode(',', array_fill(0, count($researchIds), '?')) . ")";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param(str_repeat('i', count($researchIds)), ...$researchIds);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to execute query: ' . $stmt->error);
                }
                $stmt->close();
            } else {
                throw new Exception('Failed to prepare statement: ' . $conn->error);
            }
        }

        // Delete from submission_history table
        $query = "DELETE FROM submission_history WHERE submission_id IN (SELECT id FROM submission_status WHERE submission_id IN (SELECT id FROM archive WHERE UserID = ?))";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $userId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from submission_status table
        $query = "DELETE FROM submission_status WHERE submission_id IN (SELECT id FROM archive WHERE UserID = ?)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $userId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from archive table
        $query = "DELETE FROM archive WHERE UserID = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $userId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from userprofile table
        $query = "DELETE FROM userprofile WHERE UserID = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $userId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Delete from useraccount table
        $query = "DELETE FROM useraccount WHERE UserID = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $userId);
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