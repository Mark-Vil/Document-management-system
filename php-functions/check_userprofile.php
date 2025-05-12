<?php
include 'dbconnection.php';

function fetch_user_data($user_id) {
    global $conn;

    // SQL query to fetch first_name, last_name, profile_path, email, is_adviser, and is_student
    $sql = "
        SELECT up.first_name, up.middle_name, up.last_name, up.id_number, up.profile_path, up.department, up.college, ua.email, ua.status, ua.is_emailverified, ua.is_verified, ua.is_faculty, ua.is_student
        FROM useraccount ua
        LEFT JOIN userprofile up ON ua.UserID = up.UserID
        WHERE ua.UserID = ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $middle_name, $last_name, $id_number, $profile_path, $department, $college, $email, $status, $is_emailverified, $is_verified, $is_faculty, $is_student);
        $stmt->fetch();
        $stmt->close();

        // Adjust the profile path if necessary
        if ($profile_path && strpos($profile_path, '../') === 0) {
            $profile_path = substr($profile_path, 3); 
        }

        // Determine the role
        $role = '';
        if ($is_faculty) {
            $role = 'Faculty';
        } elseif ($is_student) {
            $role = 'Student';
        }

        return [
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'id_number' => $id_number,
            'profile_path' => $profile_path,
            'department' => $department,
            'college' => $college,
            'email' => $email,
            'status' => $status,
            'is_emailverified' => $is_emailverified,
            'is_verified' => $is_verified,
            'role' => $role
        ];
    } else {
        echo "Failed to prepare statement: " . $conn->error;
        exit();
    }
}
?>