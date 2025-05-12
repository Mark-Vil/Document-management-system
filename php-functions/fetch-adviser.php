<?php
session_start();
include 'dbconnection.php';

$student_id = $_SESSION['student_id'];

// Fetch UserID from userprofile using student_id
$query = "SELECT UserID FROM userprofile WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Fetch advisor_code from userprofile using UserID
$query = "SELECT advisor_code FROM userprofile WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($advisor_code);
$stmt->fetch();
$stmt->close();

// Fetch UserID from useraccount using advisor_code
$query = "SELECT UserID FROM useraccount WHERE adviser_code = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $advisor_code); // Assuming advisor_code is a string
$stmt->execute();
$stmt->bind_result($advisor_user_id);
$stmt->fetch();
$stmt->close();

// Fetch first_name and last_name from userprofile using UserID
$query = "SELECT first_name, last_name FROM userprofile WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $advisor_user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name);
$stmt->fetch();
$stmt->close();

$response = array(
  'success' => true,
  'advisor_code' => $advisor_code,
  'advisor_name' => $first_name . ' ' . $last_name
);

echo json_encode($response);
?>