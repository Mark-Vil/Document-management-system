<?php
include 'dbconnection.php'; // Include your database connection

$collegeCode = $_GET['college_code'];
$departmentCode = $_GET['department_code'];
$year = $_GET['year'];

$query = "SELECT a.id, a.research_title, s.dateofsubmission,
          (SELECT COUNT(*) FROM downloads WHERE research_id = a.id) AS total_downloads,
          (SELECT COUNT(*) FROM views WHERE research_id = a.id) AS total_views,
           (SELECT COUNT(*) FROM citation WHERE research_id = a.id) AS total_citation
          FROM archive a
          JOIN submission_status s ON s.submission_id = a.id
          JOIN departments d ON a.faculty_code = d.department_code
          JOIN colleges c ON d.college_code = c.college_code
          WHERE c.college_code = ? AND d.department_code = ? AND YEAR(s.dateofsubmission) = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $collegeCode, $departmentCode, $year);
$stmt->execute();
$result = $stmt->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>