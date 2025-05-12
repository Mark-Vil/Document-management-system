<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['college_code'])) {
    $college_code = $_POST['college_code'];

    // Prepare the SQL query to update the status in college_account
    $sql1 = "UPDATE college_account SET status = 'Active' WHERE college_code = ?";

    // Prepare and bind
    if ($stmt1 = $conn->prepare($sql1)) {
        $stmt1->bind_param("s", $college_code);

        // Execute the statement
        if ($stmt1->execute()) {
            // Prepare the SQL query to update the status in colleges
            $sql2 = "UPDATE colleges SET status = 'USED' WHERE college_code = ?";
            if ($stmt2 = $conn->prepare($sql2)) {
                $stmt2->bind_param("s", $college_code);

                // Execute the statement
                if ($stmt2->execute()) {
                    echo json_encode(["status" => "success", "message" => "Status updated successfully."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error updating status in colleges: " . $stmt2->error]);
                }

                // Close the second statement
                $stmt2->close();
            } else {
                echo json_encode(["status" => "error", "message" => "Error preparing statement for colleges: " . $conn->error]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating status in college_account: " . $stmt1->error]);
        }

        // Close the first statement
        $stmt1->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error preparing statement for college_account: " . $conn->error]);
    }

    // Close the connection
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>