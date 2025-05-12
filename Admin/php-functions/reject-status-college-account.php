<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['college_code'])) {
    $college_code = $_POST['college_code'];

    // Prepare the SQL query to delete the row in college_account where status is 'Waiting'
    $sql = "DELETE FROM college_account WHERE college_code = ? AND status = 'Waiting'";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $college_code);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                // Update the status in the colleges table to 'No Account'
                $updateSql = "UPDATE colleges SET status = 'No Account' WHERE college_code = ?";
                if ($updateStmt = $conn->prepare($updateSql)) {
                    $updateStmt->bind_param("s", $college_code);
                    if ($updateStmt->execute()) {
                        echo json_encode(["status" => "success", "message" => "College account deleted and status updated successfully."]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Error updating college status: " . $updateStmt->error]);
                    }
                    $updateStmt->close();
                } else {
                    echo json_encode(["status" => "error", "message" => "Error preparing update statement: " . $conn->error]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "No college account found with the given college_code and status 'Waiting'."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting college account: " . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error preparing delete statement: " . $conn->error]);
    }

    // Close the connection
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>