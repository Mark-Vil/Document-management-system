<?php
include 'dbconnection.php';

if (isset($_GET['submission_id'])) {
    $submission_id = $_GET['submission_id'];

    $sql = "
        SELECT 
            a.research_title, 
            a.abstract, 
            a.keywords, 
            a.author, 
            a.co_authors,
            a.file_path, 
            ss.dateofsubmission, 
            ss.date_accepted, 
            ss.status 
        FROM 
            archive a
        JOIN 
            submission_status ss 
        ON 
            a.id = ss.submission_id 
        WHERE 
            a.id = ?
    ";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $submission_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $metadata = $result->fetch_assoc();
        $stmt->close();

        // Handle null values
        $metadata = array_map(function($value) {
            return $value ?? 'N/A';
        }, $metadata);

        echo json_encode($metadata);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No submission ID provided']);
}

$conn->close();
?>