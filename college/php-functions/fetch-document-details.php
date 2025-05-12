<?php
require 'dbconnection.php'; // Ensure this path is correct

if (isset($_GET['id'])) {
    $documentId = $_GET['id'];

    // Fetch document details
    $query = "
        SELECT 
            a.research_title AS title, 
            a.author, 
            a.co_authors, 
            ss.dateofsubmission, 
            ss.date_accepted, 
            a.adviser_name,
            ss.status, 
            a.file_path,
            ua.is_faculty,
            ua.is_student
        FROM 
            archive a
        JOIN 
            submission_status ss ON a.id = ss.submission_id
        JOIN 
            useraccount ua ON a.UserID = ua.UserID
        WHERE 
            a.id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $documentDetails = $result->fetch_assoc();

    // Return data as JSON
    echo json_encode($documentDetails);
}
?>