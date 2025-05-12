<?php
session_start();
include 'dbconnection.php';

// Check if the session variables are set
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['student_id'];
$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = null;
    $target_file = null;
    try {
        // Start transaction
        $conn->begin_transaction();

        // Validate required fields
        $required_fields = ['firstName', 'lastName', 'idnumber', 'department'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required");
            }
        }

        $first_name = ucwords(trim($_POST['firstName']));
        $middle_name = ucwords(trim($_POST['middleName']));
        $last_name = ucwords(trim($_POST['lastName']));
        $id_number = trim($_POST['idnumber']);
        $department_code = trim($_POST['department']);
        $profile_path = '';
        $current_profile_path = '';

        // Fetch department and college information
        $sql = "
            SELECT d.department_name, c.college_name, c.college_code
            FROM departments d
            JOIN colleges c ON d.college_code = c.college_code
            WHERE d.department_code = ?
        ";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare department query: " . $conn->error);
        }
        $stmt->bind_param("s", $department_code);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute department query: " . $stmt->error);
        }
        $result = $stmt->get_result();
        if (!$row = $result->fetch_assoc()) {
            throw new Exception("Department not found");
        }
        $department_name = $row['department_name'];
        $college_name = $row['college_name'];
        $college_code = $row['college_code'];
        $stmt->close();
        $stmt = null;

        // Get current profile path
        $sql = "SELECT profile_path FROM userprofile WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare profile query: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $current_profile_path = $row['profile_path'];
        }
        $stmt->close();
        $stmt = null;

        // Handle file upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../profile-photo/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . uniqid() . '_' . basename($_FILES["profile_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate image
            $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
            if ($check === false) {
                throw new Exception("File is not an image");
            }

            // Check file size (5MB limit)
            if ($_FILES["profile_image"]["size"] > 5 * 1024 * 1024) {
                throw new Exception("Profile picture limit is 5MB");
            }

            // Validate file type
            if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed");
            }

            if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                throw new Exception("Failed to upload file");
            }
            $profile_path = $target_file;
        } else {
            $profile_path = $current_profile_path;
        }

        // Check if profile exists
        $sql = "SELECT COUNT(*) as count FROM userprofile WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare count query: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();
        $stmt = null;

        // Prepare the appropriate SQL statement
        if ($count > 0) {
            $sql = "UPDATE userprofile SET 
                    first_name = ?, middle_name = ?, last_name = ?, 
                    id_number = ?, profile_path = ?, department = ?, 
                    college = ?, college_code = ? 
                    WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare update statement: " . $conn->error);
            }
            $stmt->bind_param("ssssssssi", 
                $first_name, $middle_name, $last_name, 
                $id_number, $profile_path, $department_name, 
                $college_name, $college_code, $user_id
            );
        } else {
            $sql = "INSERT INTO userprofile (
                    UserID, first_name, middle_name, last_name, 
                    id_number, department, college, college_code, profile_path
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare insert statement: " . $conn->error);
            }
            $stmt->bind_param("issssssss", 
                $user_id, $first_name, $middle_name, $last_name, 
                $id_number, $department_name, $college_name, $college_code, $profile_path
            );
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to save profile: " . $stmt->error);
        }
        $stmt->close();
        $stmt = null;

        // Commit transaction
        $conn->commit();
        $response = ['status' => 'success', 'message' => 'Profile updated successfully!'];

    } catch (Exception $e) {
        // Rollback transaction on error
        if ($conn) {
            $conn->rollback();
        }
        $response = ['status' => 'error', 'message' => $e->getMessage()];
        
        // Delete uploaded file if exists and there was an error
        if (isset($target_file) && file_exists($target_file)) {
            unlink($target_file);
        }
    } finally {
        // Close statement if still open
        if ($stmt) {
            $stmt->close();
        }
        // Close connection
        if ($conn) {
            $conn->close();
        }
    }
}

echo json_encode($response);
?>