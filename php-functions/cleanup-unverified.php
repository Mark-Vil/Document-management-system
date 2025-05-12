<?php
require 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Delete unverified account that has expired or being abandoned
    $stmt = $conn->prepare("DELETE FROM useraccount 
                           WHERE email = ? 
                           AND is_emailverified = 0 ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $conn->close();
}
?>