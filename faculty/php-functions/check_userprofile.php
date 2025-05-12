<?php
include 'dbconnection.php';

function fetch_user_data($user_id) {
    global $conn;

    // SQL query to fetch user details
    $sql = "
        SELECT 
            up.first_name, 
            up.middle_name, 
            up.last_name, 
            up.id_number, 
            ua.email,
            ua.adviser_code,
            ua.is_emailverified,
            ua.is_verified,
            d.department_name, 
            c.college_name,
            ua.is_faculty,
            ua.is_student,
            up.profile_path
            
        FROM 
            useraccount ua
        LEFT JOIN 
            userprofile up ON ua.UserID = up.UserID
        JOIN 
            departments d ON ua.department_code = d.department_code
        JOIN 
            colleges c ON d.college_code = c.college_code
        WHERE 
            ua.UserID = ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $middle_name, $last_name, $id_number, $email, $adviser_code, $is_emailverified, $is_verified, $department_name, $college_name, $is_faculty, $is_student, $profile_path);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Failed to prepare statement: " . $conn->error;
        return null;
    }

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
    } else {
        // Redirect to index page if role is unknown
        header("Location: ../index.php");
        exit();
    }

    return [
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name,
        'id_number' => $id_number,
        'email' => $email,
        'adviser_code' => $adviser_code,
        'is_emailverified' => $is_emailverified,
        'is_verified' => $is_verified,
        'department_name' => $department_name,
        'college_name' => $college_name,
        'profile_path' => $profile_path,
        'role' => $role
    ];
}
?>