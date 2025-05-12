<?php
require 'dbconnection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $advisorCode = $_POST['advisorCode'];
    $file = $_FILES['verificationFile'];

    // Validate user_id in userprofile table
    $sql_check_user = "SELECT UserID FROM userprofile WHERE UserID = ?";
    if ($stmt_check_user = $conn->prepare($sql_check_user)) {
        $stmt_check_user->bind_param("i", $user_id);
        $stmt_check_user->execute();
        $stmt_check_user->store_result();

        if ($stmt_check_user->num_rows > 0) {
            // Validate advisorCode in useraccount table
            $sql_check_advisor = "SELECT adviser_code, is_verified FROM useraccount WHERE adviser_code = ?";
            if ($stmt_check_advisor = $conn->prepare($sql_check_advisor)) {
                $stmt_check_advisor->bind_param("s", $advisorCode);
                $stmt_check_advisor->execute();
                $stmt_check_advisor->store_result();
                $stmt_check_advisor->bind_result($adviser_code, $is_verified);
                $stmt_check_advisor->fetch();

                if ($stmt_check_advisor->num_rows > 0) {
                    if ($is_verified == 0) {
                        echo json_encode(['status' => 'error', 'message' => 'This adviser is not yet verified by the system.']);
                        exit();
                    }

                    // Insert advisorCode into userprofile table
                    $sql_update_profile = "UPDATE userprofile SET advisor_code = ? WHERE UserID = ?";
                    if ($stmt_update_profile = $conn->prepare($sql_update_profile)) {
                        $stmt_update_profile->bind_param("si", $advisorCode, $user_id);
                        if ($stmt_update_profile->execute()) {
                            // Handle file upload
                            $target_dir = "../cor/";
                            $file_name = pathinfo($file["name"], PATHINFO_FILENAME);
                            $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
                            $target_file = $target_dir . $file_name . '.' . $file_extension;
                            $uploadOk = 1;
                
                            // Check if file is a PDF
                            if ($file_extension != "pdf") {
                                $uploadOk = 0;
                                echo json_encode(['status' => 'error', 'message' => 'Only PDF files are allowed.']);
                                exit();
                            }
                
                            // Check file size (limit to 1MB)
                            if ($file["size"] > 1000000) {
                                $uploadOk = 0;
                                echo json_encode(['status' => 'error', 'message' => 'File should be less than 1MB.']);
                                exit();
                            }
                
                            // Increment file name if it already exists
                            $counter = 1;
                            while (file_exists($target_file)) {
                                $target_file = $target_dir . $file_name . '_' . $counter . '.' . $file_extension;
                                $counter++;
                            }
                
                            // Attempt to upload file
                            if ($uploadOk == 1) {
                                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                                    // Remove '../' from the file path
                                    $db_file_path = str_replace('../', '', $target_file);

                                    // Update file path in userprofile table
                                    $sql_update_file = "UPDATE userprofile SET cor = ? WHERE UserID = ?";
                                    if ($stmt_update_file = $conn->prepare($sql_update_file)) {
                                        $stmt_update_file->bind_param("si", $db_file_path, $user_id);
                                        if ($stmt_update_file->execute()) {
                                            // Update status in useraccount table
                                            $sql_update_status = "UPDATE useraccount SET status = 'Waiting' WHERE UserID = ?";
                                            if ($stmt_update_status = $conn->prepare($sql_update_status)) {
                                                $stmt_update_status->bind_param("i", $user_id);
                                                if ($stmt_update_status->execute()) {
                                                    echo json_encode(['status' => 'success', 'message' => 'Account verified, file uploaded, and status updated successfully.']);
                                                } else {
                                                    echo json_encode(['status' => 'error', 'message' => 'Failed to update status in the database.']);
                                                }
                                                $stmt_update_status->close();
                                            } else {
                                                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare status update statement.']);
                                            }
                                        } else {
                                            echo json_encode(['status' => 'error', 'message' => 'Failed to update file path in the database.']);
                                        }
                                        $stmt_update_file->close();
                                    } else {
                                        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare file path update statement.']);
                                    }
                                } else {
                                    echo json_encode(['status' => 'error', 'message' => 'Failed to upload file.']);
                                }
                            }
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Failed to update advisor code in the database.']);
                        }
                        $stmt_update_profile->close();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare advisor code update statement.']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid advisor code.']);
                }
                $stmt_check_advisor->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare advisor code check statement.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid user ID.']);
        }
        $stmt_check_user->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare user ID check statement.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>