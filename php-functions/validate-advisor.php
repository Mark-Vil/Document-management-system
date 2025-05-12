<?php
include 'dbconnection.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $advisor_code = $_POST['advisor_code'];

    // Fetch advisor information
    $query = "
        SELECT ua.is_verified, up.first_name, up.last_name, up.profile_path
        FROM useraccount ua
        JOIN userprofile up ON ua.UserID = up.UserID
        WHERE ua.adviser_code = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $advisor_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $advisor_info = $result->fetch_assoc();
            // Remove '../../' from profile_path
            $advisor_info['profile_path'] = str_replace('../../', '', $advisor_info['profile_path']);
            $response['status'] = 'success';
            $response['data'] = $advisor_info;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Advisor code not found';
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to prepare statement: ' . $conn->error;
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
?>