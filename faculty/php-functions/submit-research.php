<?php
include 'dbconnection.php';
require '../../vendor/autoload.php';

use TeamTNT\TNTSearch\TNTSearch;

session_start();

// Check if the session variables are set
if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['faculty_id'];

// Fetch the adviser_code and department_code from the useraccount table
$adviser_code = '';
$department_code = '';
$user_sql = "SELECT adviser_code, department_code FROM useraccount WHERE UserID = ?";
if ($user_stmt = $conn->prepare($user_sql)) {
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_stmt->bind_result($adviser_code, $department_code);
    $user_stmt->fetch();
    $user_stmt->close();
}

if (empty($adviser_code) || empty($department_code)) {
    echo json_encode(['status' => 'error', 'message' => 'Adviser code or department code not found']);
    exit();
}

// Retrieve POST data
$research_title = ucwords(strtolower($_POST['researchtitle'])); 
$author = ucwords(strtolower($_POST['authorname']));
$co_authors = implode(', ', array_map(function($name) {
    return ucwords(strtolower($name));
}, $_POST['coauthorname'])); 
$abstract = $_POST['abstract'];
$keywords = implode(', ', $_POST['addkeywords']);

// Start transaction
$conn->begin_transaction();

try {
    // Handle file upload
    $target_dir = "../../Archive/";
    $target_file = $target_dir . basename($_FILES["uploaded_file"]["name"]);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_path = "";

    // Check if the file is a PDF
    if ($file_type != "pdf") {
        throw new Exception('Only PDF files are allowed');
    }

    if (!move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
        throw new Exception('File upload failed');
    }

    // Remove the first ../ from the file path
    $file_path = str_replace("../../", "../", $target_file);

    // Prepare SQL statement to insert into archive table
    $sql = "INSERT INTO archive (research_title, author, co_authors, abstract, keywords, file_path, UserID, faculty_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssis", $research_title, $author, $co_authors, $abstract, $keywords, $file_path, $user_id, $department_code);

        if (!$stmt->execute()) {
            throw new Exception('Failed to submit: ' . $stmt->error);
        }

        // Get the last inserted ID
        $submission_id = $stmt->insert_id;

        // Prepare SQL statement to insert into submission_status table
        $status_sql = "INSERT INTO submission_status (submission_id, dateofsubmission, status, date_accepted) VALUES (?, NOW(), 'Accepted', NOW())";
        if ($status_stmt = $conn->prepare($status_sql)) {
            $status_stmt->bind_param("i", $submission_id);

            if (!$status_stmt->execute()) {
                throw new Exception('Failed to update submission status: ' . $status_stmt->error);
            }

            // TNTSearch: Update the index with the new document
            $tnt = new TNTSearch();
            $tnt->loadConfig([
                'driver'   => 'mysql',
                'host'     => 'localhost', // Replace with your MySQL host
                'database' => 'rmis', // Replace with your MySQL database name
                'username' => 'root', // Replace with your MySQL username
                'password' => '', // Replace with your MySQL password
                'storage'  => __DIR__.'/../../indexes', // Adjust the storage path
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
            echo json_encode(['status' => 'success', 'message' => 'Submission Successful']);
        } else {
            throw new Exception('Failed to prepare status statement: ' . $conn->error);
        }
    } else {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit();
}

// Close the database connection
$conn->close();
?>