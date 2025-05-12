<?php
include 'dbconnection.php';

// Fetch data from the colleges table
$sql = "SELECT college_name, college_code, creation_date, status FROM colleges";
$result = $conn->query($sql);
?>