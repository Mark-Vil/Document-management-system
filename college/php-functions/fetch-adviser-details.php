<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Prepare the SQL query to fetch the required data
    $sql = "
        SELECT 
            ua.email, 
            ua.status, 
            ua.is_emailverified,
            ua.adviser_code, 
            up.first_name, 
            up.middle_name, 
            up.last_name, 
            up.id_number 
        FROM 
            useraccount ua 
        JOIN 
            userprofile up ON ua.UserID = up.UserID 
        WHERE 
            ua.email = ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email, $status, $is_emailverified, $adviser_code, $first_name, $middle_name, $last_name, $id_number);
        if ($stmt->fetch()) {
            $response = array(
                'status' => 'success',
                'data' => array(
                    'email' => $email,
                    'status' => $status,
                    'is_emailverified' => $is_emailverified,
                    'first_name' => $first_name,
                    'middle_name' => $middle_name,
                    'last_name' => $last_name,
                    'id_number' => $id_number,
                    'adviser_code' => $adviser_code
                )
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'No data found for the provided email.'
            );
        }
        $stmt->close();
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to prepare statement: ' . $conn->error
        );
    }

    $conn->close();
    echo json_encode($response);
}
?>