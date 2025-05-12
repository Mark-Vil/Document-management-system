<?php
include 'dbconnection.php';

// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    // Redirect to the login page if the session is not set
    header("Location: ../index.php");
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['faculty_id'];

// Prepare the SQL query to fetch the adviser_code from useraccount table
$sql_adviser_code = "SELECT adviser_code FROM useraccount WHERE UserID = ?";
if ($stmt_adviser_code = $conn->prepare($sql_adviser_code)) {
    $stmt_adviser_code->bind_param("i", $user_id);
    $stmt_adviser_code->execute();
    $stmt_adviser_code->bind_result($adviser_code);
    $stmt_adviser_code->fetch();
    $stmt_adviser_code->close();
} else {
    echo "Failed to prepare statement: " . $conn->error;
    exit();
}

// Prepare the SQL query to fetch the required data from userprofile and useraccount tables
$sql = "
    SELECT 
        ua.email, 
        ua.is_emailverified,
        up.id_number, 
        up.first_name, 
        up.middle_name, 
        up.last_name, 
        up.cor 
    FROM 
        userprofile up 
    JOIN 
        useraccount ua ON up.UserID = ua.UserID 
    WHERE 
        up.advisor_code = ? AND ua.status = 'Waiting'
";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $adviser_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $results = [];
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
} else {
    echo "Failed to prepare statement: " . $conn->error;
    exit();
}

$conn->close();
?>