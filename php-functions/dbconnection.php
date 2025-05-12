<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "if0_37772329_rmis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

