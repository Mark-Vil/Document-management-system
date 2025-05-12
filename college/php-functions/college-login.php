<?php
include 'dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM college_account WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check the account status
        if ($row['status'] == 'Waiting') {
            echo json_encode([
                'status' => 'error',
                'message' => 'The account is currently waiting for activation.'
            ]);
        } elseif ($row['status'] == 'Deactivated') {
            echo json_encode([
                'status' => 'error',
                'message' => 'The account is deactivated.'
            ]);
        } else {
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION['email'] = $row['email'];
                $_SESSION['college_code'] = $row['college_code'];

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid email or password.'
                ]);
            }
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid email or password.'
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>