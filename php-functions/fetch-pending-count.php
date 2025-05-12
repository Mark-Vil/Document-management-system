<?php
include 'dbconnection.php';

$sql = "
    SELECT COUNT(*) as pending_count 
    FROM archive a 
    JOIN submission_status ss ON a.id = ss.submission_id 
    WHERE ss.status IN ('Revise', 'Updated', 'Pending')
";

if ($result = $conn->query($sql)) {
    $row = $result->fetch_assoc();
    echo json_encode(['count' => $row['pending_count']]);
} else {
    echo json_encode(['error' => 'Failed to fetch count']);
}

$conn->close();
?>