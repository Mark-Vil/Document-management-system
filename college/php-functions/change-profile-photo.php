<?php
session_start();
include 'dbconnection.php';

// Check if the session variables are set
if (!isset($_SESSION['college_code'])) {
    // Return a JSON response indicating the user is not logged in
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$college_code = $_SESSION['college_code'];
$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the current image path from the database
    $sql = "SELECT image_path FROM college_account WHERE college_code = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $college_code);
        $stmt->execute();
        $stmt->bind_result($current_image_path);
        $stmt->fetch();
        $stmt->close();
    } else {
        $response['message'] = "Failed to prepare statement: " . $conn->error;
        echo json_encode($response);
        exit();
    }

    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../profile-photo/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['message'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_image"]["size"] > 500000) {
            $response['message'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $response['status'] = 'error';
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // Delete the current image file if it exists
                if ($current_image_path && file_exists($current_image_path)) {
                    unlink($current_image_path);
                }

                // Update the profile image path in the database
                $sql = "UPDATE college_account SET image_path = ? WHERE college_code = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ss", $target_file, $college_code);
                    $stmt->execute();
                    $stmt->close();
                    $response = ['status' => 'success', 'message' => 'Profile image updated successfully!', 'profile_path' => $target_file];
                } else {
                    $response['message'] = "Failed to prepare statement: " . $conn->error;
                }
            } else {
                $response['message'] = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $response['message'] = "No file was uploaded.";
    }
}

// Return the JSON response
echo json_encode($response);
?>