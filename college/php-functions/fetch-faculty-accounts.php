<?php
include 'dbconnection.php';

// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$college_code = $_SESSION['college_code'];

// Prepare the SQL query to fetch adviser accounts
$sql = "
    SELECT 
        ua.email, 
        up.first_name, 
        up.last_name,
        ua.creation_date, 
        ua.status
    FROM 
        useraccount ua
    JOIN 
        userprofile up ON ua.UserID = up.UserID
    JOIN 
        departments d ON ua.department_code = d.department_code
    JOIN 
        colleges c ON d.college_code = c.college_code
    WHERE 
        ua.is_faculty = 1 AND 
        c.college_code = ?
";

// Execute the query
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $college_code);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare the response array
    $facultyAccounts = array();

    if ($result->num_rows > 0) {
        // Fetch all rows and store them in the response array
        while ($row = $result->fetch_assoc()) {
            $facultyAccounts[] = $row;
        }
    } else {
        $facultyAccounts = null;
    }

    $stmt->close();
} else {
    echo "Failed to prepare statement: " . $conn->error;
    exit();
}

// Prepare the SQL query to fetch department names
$sql = "
    SELECT 
        department_code, 
        department_name 
    FROM 
        departments 
    WHERE 
        college_code = ?
";

// Execute the query
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $college_code);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare the response array
    $departments = array();

    if ($result->num_rows > 0) {
        // Fetch all rows and store them in the response array
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    } else {
        $departments = null;
    }

    $stmt->close();
} else {
    echo "Failed to prepare statement: " . $conn->error;
    exit();
}

$conn->close();
?>