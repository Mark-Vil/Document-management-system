<?php
session_start();
include 'dbconnection.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

try {
    // Get the adviser_code for the current faculty
    $sql_get_adviser_code = "SELECT adviser_code FROM useraccount WHERE UserID = ?";
    if ($stmt_get_adviser_code = $conn->prepare($sql_get_adviser_code)) {
        $stmt_get_adviser_code->bind_param("i", $faculty_id);
        $stmt_get_adviser_code->execute();
        $stmt_get_adviser_code->bind_result($adviser_code);
        $stmt_get_adviser_code->fetch();
        $stmt_get_adviser_code->close();

        if ($adviser_code) {
            // Count the rows in userprofile that match the adviser_code and have status "Waiting" in useraccount
            $sql_count = "
                SELECT COUNT(up.UserID) as count
                FROM userprofile up
                JOIN useraccount ua ON up.UserID = ua.UserID
                WHERE up.advisor_code = ? AND ua.status = 'Waiting'
            ";

            if ($stmt_count = $conn->prepare($sql_count)) {
                $stmt_count->bind_param("s", $adviser_code);
                $stmt_count->execute();
                $stmt_count->bind_result($count);
                $stmt_count->fetch();
                $stmt_count->close();

                $response = ['status' => 'success', 'count' => $count];
            } else {
                throw new Exception('Failed to prepare count statement: ' . $conn->error);
            }
        } else {
            throw new Exception('Adviser code not found for the current faculty');
        }
    } else {
        throw new Exception('Failed to prepare adviser code statement: ' . $conn->error);
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
?>