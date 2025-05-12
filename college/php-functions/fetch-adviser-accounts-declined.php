<?php
include 'dbconnection.php';

// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$department_code = isset($_POST['department_code']) ? $_POST['department_code'] : '';
$college_code = isset($_POST['college_code']) ? $_POST['college_code'] : '';

if (empty($college_code)) {
    echo json_encode(array("error" => "College code is required"));
    exit();
}

// Prepare the SQL query to fetch adviser accounts with status "Waiting"
$sql = "
    SELECT 
        ua.email, 
        ua.creation_date, 
        ua.status
    FROM 
        useraccount ua
    JOIN 
        departments d ON ua.department_code = d.department_code
    WHERE 
        ua.is_faculty = 1 AND 
        ua.status = 'Declined' AND 
        d.college_code = ?
";

if (!empty($department_code)) {
    $sql .= " AND d.department_code = ?";
}

// Execute the query
if ($stmt = $conn->prepare($sql)) {
    if (!empty($department_code)) {
        $stmt->bind_param("ss", $college_code, $department_code);
    } else {
        $stmt->bind_param("s", $college_code);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare the response array
    $facultyAccounts = array();

    if ($result->num_rows > 0) {
        // Fetch all rows and store them in the response array
        while ($row = $result->fetch_assoc()) {
            $facultyAccounts[] = $row;
        }
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Failed to prepare statement: " . $conn->error));
    exit();
}

$conn->close();
echo json_encode($facultyAccounts);
?>