<?php
include 'dbconnection.php';

$response = array();

if (isset($_POST['college_name']) && isset($_POST['college_code']) && isset($_POST['original_college_code'])) {
    $college_name = $_POST['college_name'];
    $college_code = $_POST['college_code'];
    $original_college_code = $_POST['original_college_code'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Disable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=0");

        // Update the college_account table
        $sql = "UPDATE college_account SET college_code = ? WHERE college_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $college_code, $original_college_code);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update college_account table: ' . $stmt->error);
        }
        $stmt->close();

        // Update the departments table
        $sql = "UPDATE departments SET college_code = ? WHERE college_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $college_code, $original_college_code);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update departments table: ' . $stmt->error);
        }
        $stmt->close();

        // Update the colleges table
        $sql = "UPDATE colleges SET college_name = ?, college_code = ? WHERE college_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $college_name, $college_code, $original_college_code);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update colleges table: ' . $stmt->error);
        }
        $stmt->close();

        // Enable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=1");

        // Commit transaction
        $conn->commit();

        $response['status'] = 'success';
        $response['message'] = 'College updated successfully.';
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

        // Enable foreign key checks in case of error
        $conn->query("SET FOREIGN_KEY_CHECKS=1");

        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>