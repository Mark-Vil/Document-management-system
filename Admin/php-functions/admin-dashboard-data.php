<?php
include 'dbconnection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];

    // Fetch all available years from submission_status table
    $sql_years = "SELECT DISTINCT YEAR(dateofsubmission) AS year FROM submission_status ORDER BY year DESC";
    $result_years = $conn->query($sql_years);
    $years = array();
    while ($row = $result_years->fetch_assoc()) {
        $years[] = $row['year'];
    }

    // Count total colleges
    $sql_colleges = "SELECT COUNT(*) AS total FROM colleges";
    $result_colleges = $conn->query($sql_colleges);
    $totalColleges = $result_colleges->fetch_assoc()['total'];

    // Count total departments
    $sql_departments = "SELECT COUNT(*) AS total FROM departments";
    $result_departments = $conn->query($sql_departments);
    $totalDepartments = $result_departments->fetch_assoc()['total'];

    // Count total documents with status "Accepted" or "Locked"
    $sql_documents = "
        SELECT COUNT(*) AS total 
        FROM archive a
        JOIN submission_status ss ON a.id = ss.submission_id
        WHERE ss.status IN ('Accepted', 'Locked') AND YEAR(ss.dateofsubmission) = ?
    ";
    $stmt_documents = $conn->prepare($sql_documents);
    $stmt_documents->bind_param("i", $year);
    $stmt_documents->execute();
    $result_documents = $stmt_documents->get_result();
    $totalDocuments = $result_documents->fetch_assoc()['total'];

    $response = [
        'status' => 'success',
        'years' => $years,
        'totalColleges' => $totalColleges,
        'totalDepartments' => $totalDepartments,
        'totalDocuments' => $totalDocuments
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method.'
    ];
}

$conn->close();
echo json_encode($response);
?>