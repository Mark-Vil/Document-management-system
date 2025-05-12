<?php
include 'dbconnection.php';

$response = array();

if (isset($_POST['college_code'])) {
    $college_code = $_POST['college_code'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete the college from the colleges table
        $sql = "DELETE FROM colleges WHERE college_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $college_code);

        if (!$stmt->execute()) {
            throw new Exception('Failed to delete college: ' . $stmt->error);
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();

        $response['status'] = 'success';
        $response['message'] = 'College deleted successfully.';
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

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