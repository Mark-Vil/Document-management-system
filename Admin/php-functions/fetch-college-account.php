<?php
include 'dbconnection.php';

// Prepare the SQL query to fetch data from both tables
$sql = "
    SELECT 
        colleges.college_name, 
        colleges.creation_date, 
        college_account.status,
        college_account.college_code
    FROM 
        colleges 
    INNER JOIN 
        college_account 
    ON 
        colleges.college_code = college_account.college_code
";

// Execute the query
$result2 = $conn->query($sql);

// Prepare the response array
$collegeAccounts = array();

if ($result2->num_rows > 0) {
    // Fetch all rows and store them in the response array
    while ($row = $result2->fetch_assoc()) {
        $collegeAccounts[] = $row;
    }
} else {
    $collegeAccounts = null;
}

// Close the connection
$conn->close();
?>