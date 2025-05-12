<?php
include 'dbconnection.php';
require '../vendor/autoload.php';

use TeamTNT\TNTSearch\TNTSearch;

session_start();


if (!isset($_SESSION['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}


$research_title = ucwords(strtolower($_POST['researchtitle'])); 
$author = ucwords(strtolower($_POST['authorname']));
$co_authors = implode(', ', array_map(function($name) {
    return ucwords(strtolower($name));
}, $_POST['coauthorname'])); 
$abstract = $_POST['abstract'];
$keywords = implode(', ', $_POST['addkeywords']);

// Check which advisor code is set
if (!empty($_POST['advisorInput'])) {
    $advisorcode = $_POST['advisorInput'];
} elseif (!empty($_POST['advisorSelect'])) {
    $advisorcode = $_POST['advisorSelect'];
} else {
    $advisorcode = ''; // Default value if neither is set
}
// Transaction Start
$conn->begin_transaction();

try {
   
    $sql = "SELECT UserID, department_code FROM useraccount WHERE adviser_code = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $advisorcode);
        $stmt->execute();
        $stmt->bind_result($advisor_user_id, $department_code);
        $stmt->fetch();
        $stmt->close();

        if (empty($advisor_user_id)) {
            throw new Exception('No advisor code match found');
        }
    } else {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

    
    $sql = "SELECT first_name, last_name FROM userprofile WHERE UserID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $advisor_user_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $last_name);
        $stmt->fetch();
        $stmt->close();

        $adviser_name = $first_name . ' ' . $last_name;
    } else {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

   
    $target_dir = "../Archive/";
    $target_file = $target_dir . basename($_FILES["uploaded_file"]["name"]);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_path = "";

    if ($file_type != "pdf") {
        throw new Exception('Only PDF files are allowed');
    }

    if (!move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
        throw new Exception('File upload failed');
    }
    $file_path = $target_file;

 
    $session_user_id = $_SESSION['student_id'];

   
    $sql = "INSERT INTO archive (research_title, author, co_authors, abstract, keywords, file_path, adviser_name, UserID, faculty_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssii", $research_title, $author, $co_authors, $abstract, $keywords, $file_path, $adviser_name, $session_user_id, $department_code);

        if (!$stmt->execute()) {
            throw new Exception('Failed to submit: ' . $stmt->error);
        }

        
        $submission_id = $stmt->insert_id;
        $stmt->close();
    } else {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

   
    $sql_status = "INSERT INTO submission_status (submission_id, dateofsubmission, status, submission_code) VALUES (?, ?, ?, ?)";
    if ($stmt_status = $conn->prepare($sql_status)) {
        $dateofsubmission = date('Y-m-d H:i:s');
        $status = "Pending";
        $stmt_status->bind_param("isss", $submission_id, $dateofsubmission, $status, $advisorcode);

        if (!$stmt_status->execute()) {
            throw new Exception('Failed to update submission status: ' . $stmt_status->error);
        }
        $stmt_status->close();
    } else {
        throw new Exception('Failed to prepare status statement: ' . $conn->error);
    }

    
    $tnt = new TNTSearch();
    $tnt->loadConfig([
        'driver'   => 'mysql',
        'host'     => 'localhost', 
        'database' => 'rmis',
        'username' => 'root',
        'password' => '', 
        'storage'  => __DIR__.'/../indexes',
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

    $title = "Review Submission";
    $message = "Research submission titled \"$research_title\" has been submitted to you, please check it.";
    $notification_sql = "INSERT INTO notifications (UserID, title, message) VALUES (?, ?, ?)";
    if ($notification_stmt = $conn->prepare($notification_sql)) {
        $notification_stmt->bind_param("iss", $advisor_user_id, $title, $message);
        if (!$notification_stmt->execute()) {
            throw new Exception('Failed to insert notification: ' . $notification_stmt->error);
        }
        $notification_stmt->close();
    } else {
        throw new Exception('Failed to prepare notification statement: ' . $conn->error);
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Submission successful']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit();
}


$conn->close();
?>

