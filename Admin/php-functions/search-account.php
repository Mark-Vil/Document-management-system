<?php
include 'dbconnection.php';

header('Content-Type: application/json');

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchTerm)) {
    $searchTerm = '%' . $searchTerm . '%';
    $sql = "
        SELECT ua.UserID, ua.email, up.first_name, up.middle_name, up.last_name
        FROM useraccount ua
        LEFT JOIN userprofile up ON ua.UserID = up.UserID
        WHERE ua.email LIKE ? OR up.first_name LIKE ? OR up.middle_name LIKE ? OR up.last_name LIKE ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        // Replace null values with empty strings
        foreach ($users as &$user) {
            foreach ($user as $key => $value) {
                if (is_null($value)) {
                    $user[$key] = '';
                }
            }
        }

        echo json_encode($users);
    } else {
        echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>