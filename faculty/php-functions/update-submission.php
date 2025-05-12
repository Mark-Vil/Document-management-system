<?php
include 'dbconnection.php';

require '../../vendor/autoload.php';

use TeamTNT\TNTSearch\TNTSearch;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submission_id = $_POST['submission_id'];
    $research_title = $_POST['research_title'];
    $author = $_POST['author'];
    $co_authors = implode(', ', array_map(function($name) {
        return ucwords(strtolower($name));
    }, $_POST['coauthorname'])); 
    $abstract = $_POST['abstract'];
    $keywords = implode(', ', $_POST['addkeywords']);
    $file_path = '';

    // Start transaction
    $conn->begin_transaction();

    try {
        // Fetch the current data from the archive table
        $sql = "SELECT * FROM archive WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $submission_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_data = $result->fetch_assoc();
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Fetch the id and comments from the submission_status table
        $sql_status = "SELECT id, dateofsubmission, comments FROM submission_status WHERE submission_id = ?";
        if ($stmt_status = $conn->prepare($sql_status)) {
            $stmt_status->bind_param("i", $submission_id);
            $stmt_status->execute();
            $result_status = $stmt_status->get_result();
            $status_data = $result_status->fetch_assoc();
            $stmt_status->close();
        } else {
            throw new Exception('Failed to prepare status statement: ' . $conn->error);
        }

        if (!$status_data) {
            throw new Exception('Submission ID does not exist in submission_status table');
        }

        // Insert the current data into the submission_history table
        $sql_history = "INSERT INTO submission_history (submission_id, research_title, author, co_authors, abstract, keywords, file_path, UserID, adviser_name, faculty_code, dateofsubmission, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt_history = $conn->prepare($sql_history)) {
            $stmt_history->bind_param(
                "isssssssssss",
                $status_data['id'], // Use 'id' instead of 'submission_id'
                $current_data['research_title'],
                $current_data['author'],
                $current_data['co_authors'],
                $current_data['abstract'],
                $current_data['keywords'],
                $current_data['file_path'],
                $current_data['UserID'],
                $current_data['adviser_name'],
                $current_data['faculty_code'],
                $status_data['dateofsubmission'],
                $status_data['comments']
            );
            $stmt_history->execute();
            $stmt_history->close();
        } else {
            throw new Exception('Failed to prepare history statement: ' . $conn->error);
        }

        // Handle file upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../../Archive/";
            $original_file_name = pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME);
            $original_file_extension = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
            $target_file = $target_dir . basename($_FILES["file"]["name"]);

            // Check if the file is a PDF
            if ($original_file_extension != "pdf") {
                throw new Exception('Only PDF files are allowed');
            }

            // Increment the file name if it already exists
            $counter = 1;
            while (file_exists($target_file)) {
                $target_file = $target_dir . $original_file_name . '_' . $counter . '.' . $original_file_extension;
                $counter++;
            }

            // Move the new file to the target directory
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $file_path = $target_file;
            } else {
                throw new Exception('File upload failed');
            }
        } else {
            // If no new file is uploaded, keep the current file path
            $file_path = $current_data['file_path'];
        }

        // Update the archive table with the new details
        $sql = "UPDATE archive SET research_title = ?, author = ?, co_authors = ?, abstract = ?, keywords = ?, file_path = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssi", $research_title, $author, $co_authors, $abstract, $keywords, $file_path, $submission_id);
            if ($stmt->execute()) {
                // Update the submission_status table with the new status
                $status = 'Accepted'; // Example status, change as needed
                $sql_status_update = "UPDATE submission_status SET status = ? WHERE submission_id = ?";
                if ($stmt_status_update = $conn->prepare($sql_status_update)) {
                    $stmt_status_update->bind_param("si", $status, $submission_id);
                    if ($stmt_status_update->execute()) {
                        // TNTSearch: Update the index with the new document
                        $tnt = new TNTSearch();
                        $tnt->loadConfig([
                            'driver'   => 'mysql',
                            'host'     => 'localhost', 
                            'database' => 'rmis',
                            'username' => 'root',
                            'password' => '', 
                            'storage'  => __DIR__.'/../../indexes',
                        ]);

                        $tnt->selectIndex("archive.index");
                        $index = $tnt->getIndex();
                        $index->insert([
                            'id' => $submission_id,
                            'research_title' => $research_title,
                            'author' => $author,
                            'abstract' => $abstract,
                            'keywords' => $keywords
                        ]);

                        // Commit transaction
                        $conn->commit();
                        echo json_encode(['status' => 'success', 'message' => 'Submission updated successfully.']);
                    } else {
                        throw new Exception('Failed to update submission status.');
                    }
                    $stmt_status_update->close();
                } else {
                    throw new Exception('Failed to prepare status update statement: ' . $conn->error);
                }
            } else {
                throw new Exception('Failed to update submission.');
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>