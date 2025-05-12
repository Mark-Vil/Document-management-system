<?php
include 'dbconnection.php';

session_start();

$response = array();

if (isset($_SESSION['faculty_id']) && isset($_POST['query'])) {
    $faculty_id = $_SESSION['faculty_id'];
    $searchQuery = $_POST['query'];

    // Get adviser_code from useraccount table
    $adviser_code = '';
    $stmt = $conn->prepare("SELECT adviser_code FROM useraccount WHERE UserID = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $stmt->bind_result($adviser_code);
    $stmt->fetch();
    $stmt->close();

    if ($adviser_code) {
        // Query the archive table
        $stmt = $conn->prepare("
            SELECT a.research_title, a.abstract, ss.dateofsubmission
            FROM archive a
            JOIN submission_status ss ON a.id = ss.submission_id
            WHERE (a.research_title LIKE ? OR a.abstract LIKE ?) AND ss.submission_code = ? AND (ss.status = 'Locked' OR ss.status = 'Accepted')
        ");
        $searchTerm = '%' . $searchQuery . '%';
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $adviser_code);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }

        $stmt->close();
    } else {
        $response = ['error' => 'Adviser code not found'];
    }
} else {
    $response = ['error' => 'Invalid session or query'];
}

$conn->close();
echo json_encode($response);
?>