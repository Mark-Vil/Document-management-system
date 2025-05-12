<?php
include 'dbconnection.php';
session_start(); // Start the session

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];
$collegeCode = $_POST['collegeCode'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare the response array
$response = array();

// Check if the college code exists in the colleges table
$stmt = $conn->prepare("SELECT college_code FROM colleges WHERE college_code = ?");
$stmt->bind_param("i", $collegeCode);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // College code exists, proceed with further checks
    $stmt->close();

    // Check if the college code already exists in the college_account table
    $stmt = $conn->prepare("SELECT college_code FROM college_account WHERE college_code = ?");
    $stmt->bind_param("i", $collegeCode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // College code already exists in the college_account table
        $response['status'] = 'error';
        $response['message'] = 'Error: ERROR.';
    } else {
        // College code does not exist in the college_account table, proceed with insertion
        $stmt->close();

        // Insert the data into the college_account table with status 'Waiting'
        $status = 'Waiting';
        $stmt = $conn->prepare("INSERT INTO college_account (email, password, college_code, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $email, $hashedPassword, $collegeCode, $status);

        if ($stmt->execute()) {
            // Update the status in the colleges table to 'Pending'
            $stmt->close();
            $stmt = $conn->prepare("UPDATE colleges SET status = 'Pending' WHERE college_code = ?");
            $stmt->bind_param("i", $collegeCode);
            $stmt->execute();

            $response['status'] = 'success';
            $response['message'] = 'Account created successfully. We will review your account shortly.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $stmt->error;
        }
    }
} else {
    // College code does not exist
    $response['status'] = 'error';
    $response['message'] = 'Invalid college code';
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>