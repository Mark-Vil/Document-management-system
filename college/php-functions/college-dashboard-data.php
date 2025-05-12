<?php
include 'dbconnection.php';
session_start();

// Check if the college_code session variable is set
if (!isset($_SESSION['college_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
    exit();
}

$college_code = $_SESSION['college_code'];
$current_year = date("Y");
$selected_year = isset($_POST['year']) ? $_POST['year'] : null;

try {
    // Fetch total documents
    $sqlTotalDocuments = "
        SELECT COUNT(a.id) AS total_documents
        FROM archive a
        LEFT JOIN departments d ON a.faculty_code = d.department_code
        LEFT JOIN submission_status ss ON a.id = ss.submission_id
        WHERE d.college_code = ?
    ";
    if ($selected_year) {
        $sqlTotalDocuments .= " AND YEAR(ss.dateofsubmission) = ?";
    }
    $stmtTotalDocuments = $conn->prepare($sqlTotalDocuments);
    if ($selected_year) {
        $stmtTotalDocuments->bind_param("si", $college_code, $selected_year);
    } else {
        $stmtTotalDocuments->bind_param("s", $college_code);
    }
    $stmtTotalDocuments->execute();
    $resultTotalDocuments = $stmtTotalDocuments->get_result();
    $totalDocuments = $resultTotalDocuments->fetch_assoc()['total_documents'];
    $stmtTotalDocuments->close();

    // Fetch total accepted
    $sqlTotalAccepted = "
        SELECT COUNT(a.id) AS total_accepted
        FROM archive a
        LEFT JOIN departments d ON a.faculty_code = d.department_code
        LEFT JOIN submission_status ss ON a.id = ss.submission_id
        WHERE d.college_code = ?
        AND (ss.status = 'Accepted' OR ss.status = 'Locked')
    ";
    if ($selected_year) {
        $sqlTotalAccepted .= " AND YEAR(ss.dateofsubmission) = ?";
    }
    $stmtTotalAccepted = $conn->prepare($sqlTotalAccepted);
    if ($selected_year) {
        $stmtTotalAccepted->bind_param("si", $college_code, $selected_year);
    } else {
        $stmtTotalAccepted->bind_param("s", $college_code);
    }
    $stmtTotalAccepted->execute();
    $resultTotalAccepted = $stmtTotalAccepted->get_result();
    $totalAccepted = $resultTotalAccepted->fetch_assoc()['total_accepted'];
    $stmtTotalAccepted->close();

    // Fetch total ongoing
    $sqlTotalOngoing = "
        SELECT COUNT(a.id) AS total_ongoing
        FROM archive a
        LEFT JOIN departments d ON a.faculty_code = d.department_code
        LEFT JOIN submission_status ss ON a.id = ss.submission_id
        WHERE d.college_code = ?
        AND (ss.status = 'Pending' OR ss.status = 'Revise' OR ss.status = 'Updated')
        AND YEAR(ss.dateofsubmission) = ?
    ";
    $stmtTotalOngoing = $conn->prepare($sqlTotalOngoing);
    $stmtTotalOngoing->bind_param("si", $college_code, $current_year);
    $stmtTotalOngoing->execute();
    $resultTotalOngoing = $stmtTotalOngoing->get_result();
    $totalOngoing = $resultTotalOngoing->fetch_assoc()['total_ongoing'];
    $stmtTotalOngoing->close();

    echo json_encode([
        'status' => 'success',
        'total_documents' => $totalDocuments,
        'total_accepted' => $totalAccepted,
        'total_ongoing' => $totalOngoing
    ]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>