<?php
session_start();

// Unset specific session variables
unset($_SESSION['student_id']);

// Destroy the session if no other session variables are set
if (empty($_SESSION)) {
    session_destroy();
}

// Redirect to login page
header("Location: ../index.php");
exit();
?>