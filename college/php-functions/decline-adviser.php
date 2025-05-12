<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Update the status of the adviser account to 'Active'
    $sql = "UPDATE useraccount SET status = 'Declined' WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $response = array(
                "title" => "Success",
                "message" => "Adviser account declined successfully.",
                "status" => "success"
            );
        } else {
            $response = array(
                "title" => "Error",
                "message" => "Error: " . $stmt->error,
                "status" => "error"
            );
        }
        $stmt->close();
    } else {
        $response = array(
            "title" => "Error",
            "message" => "Failed to prepare statement: " . $conn->error,
            "status" => "error"
        );
    }

    $conn->close();
    echo json_encode($response);
}
?>