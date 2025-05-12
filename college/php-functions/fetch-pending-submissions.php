<?php
// include 'dbconnection.php';
// session_start();

// // Check if the college_code session variable is set
// if (!isset($_SESSION['college_code'])) {
//     echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
//     exit();
// }

// $college_code = $_SESSION['college_code'];

// // Dummy data for testing
// $data = [
//     ['department_name' => 'Information Technology', 'total_paper' => 5],
//     ['department_name' => 'Computer Science', 'total_paper' => 3],
//     ['department_name' => 'Mathematics', 'total_paper' => 2],
//     ['department_name' => 'Physics', 'total_paper' => 4],
//     ['department_name' => 'Chemistry', 'total_paper' => 1]
// ];

// echo json_encode($data);
?>
<?php
include 'dbconnection.php';
session_start();

// Check if the college_code session variable is set
if (!isset($_SESSION['college_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'College code not set in session']);
    exit();
}

$college_code = $_SESSION['college_code'];

try {
    $sql = "
        SELECT 
            d.department_name, 
            COUNT(a.id) AS total_paper 
        FROM 
            departments d
        LEFT JOIN 
            archive a 
        ON 
            d.department_code = a.faculty_code 
        LEFT JOIN 
            submission_status ss 
        ON 
            a.id = ss.submission_id 
        WHERE 
            d.college_code = ?
            AND (ss.status = 'Pending' OR ss.status = 'Revise' OR ss.status = 'Updated')
        GROUP BY 
            d.department_name
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $college_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    echo json_encode($data);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>