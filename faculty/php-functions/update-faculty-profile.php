<?php
include 'dbconnection.php';

session_start();

// Check if the session variables are set
if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

function generateUniqueAdviserCode($conn) {
    do {
        // Generate 8 digit random number
        $code = str_pad(mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        
        // Check if code exists
        $stmt = $conn->prepare("SELECT adviser_code FROM useraccount WHERE adviser_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
    } while($exists);
    
    return $code;
}

// Retrieve POST data
$user_id = $_POST['faculty_id'];
$first_name = ucwords(strtolower($_POST['firstName']));
$middle_name = ucwords(strtolower($_POST['middleName']));
$last_name = ucwords(strtolower($_POST['lastName']));
$id_number = ucwords(strtolower($_POST['id_number']));
if (empty($_POST['code'])) {
    $adviser_code = generateUniqueAdviserCode($conn);
} else {
    $adviser_code = ucwords(strtolower($_POST['code']));
}

// Validate input
if (empty($user_id) || empty($first_name) || empty($last_name) || empty($id_number)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}



// Initialize profile_path
$profile_path = '';
$response_messages = [];
$uploaded_file_path = '';

// Start transaction
$conn->begin_transaction();

try {
    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../profile-photo/";
        $original_filename = pathinfo($_FILES["profile_image"]["name"], PATHINFO_FILENAME);
        $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $uploadOk = 1;

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            throw new Exception('File is not an image.');
        }

        // Check file size
        if ($_FILES["profile_image"]["size"] > 10485760) { // 10 MB in bytes
            throw new Exception('Sorry, your image file is too large.');
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
        }

        // Increment filename if it already exists
        $i = 1;
        while (file_exists($target_file)) {
            $target_file = $target_dir . $original_filename . '_' . $i . '.' . $imageFileType;
            $i++;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            throw new Exception('File upload failed.');
        } else {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_path = $target_file;
                $uploaded_file_path = $target_file; // Store the uploaded file path
                // $response_messages[] = 'File uploaded successfully.';
            } else {
                throw new Exception('Sorry, there was an error uploading your file.');
            }
        }
    }

    // Check if the user exists in userprofile
    $sql_check_profile = "SELECT UserID, profile_path FROM userprofile WHERE UserID = ?";
    if ($stmt_check_profile = $conn->prepare($sql_check_profile)) {
        $stmt_check_profile->bind_param("i", $user_id);
        $stmt_check_profile->execute();
        $stmt_check_profile->store_result();
        $stmt_check_profile->bind_result($existing_user_id, $existing_profile_path);
        $stmt_check_profile->fetch();

        if ($stmt_check_profile->num_rows > 0) {
            // User exists in userprofile, perform update
            $sql_update_profile = "UPDATE userprofile SET first_name = ?, middle_name = ?, last_name = ?, id_number = ?, profile_path = ? WHERE UserID = ?";
            if ($stmt_update_profile = $conn->prepare($sql_update_profile)) {
                // Use existing profile path if no new image is uploaded
                if (empty($profile_path)) {
                    $profile_path = $existing_profile_path;
                } else {
                    // Delete the existing profile image if a new one is uploaded
                    if ($existing_profile_path && file_exists($existing_profile_path)) {
                        unlink($existing_profile_path);
                    }
                }

                $stmt_update_profile->bind_param("sssssi", $first_name, $middle_name, $last_name, $id_number, $profile_path, $user_id);
                if ($stmt_update_profile->execute()) {
                    // $response_messages[] = 'Profile updated successfully';
                } else {
                    throw new Exception('Failed to update profile: ' . $stmt_update_profile->error);
                }
                $stmt_update_profile->close();
            } else {
                throw new Exception('Failed to prepare update statement: ' . $conn->error);
            }
        } else {
            // User does not exist in userprofile, perform insert
            $sql_insert_profile = "INSERT INTO userprofile (UserID, first_name, middle_name, last_name, id_number, profile_path) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt_insert_profile = $conn->prepare($sql_insert_profile)) {
                $stmt_insert_profile->bind_param("isssss", $user_id, $first_name, $middle_name, $last_name, $id_number, $profile_path);
                if ($stmt_insert_profile->execute()) {
                    // $response_messages[] = 'Profile created successfully';
                } else {
                    throw new Exception('Failed to create profile: ' . $stmt_insert_profile->error);
                }
                $stmt_insert_profile->close();
            } else {
                throw new Exception('Failed to prepare insert statement: ' . $conn->error);
            }
        }
        $stmt_check_profile->close();
    } else {
        throw new Exception('Failed to prepare check statement: ' . $conn->error);
    }

    // Check if adviser code already exists for another user
    $sql_check_adviser_code = "SELECT UserID FROM useraccount WHERE adviser_code = ? AND UserID != ?";
    if ($stmt_check_adviser_code = $conn->prepare($sql_check_adviser_code)) {
        $stmt_check_adviser_code->bind_param("si", $adviser_code, $user_id);
        $stmt_check_adviser_code->execute();
        $stmt_check_adviser_code->store_result();
        // $response_messages[] = json_encode(["Step" => "Check adviser code for another user", "Num rows" => $stmt_check_adviser_code->num_rows]);
        if ($stmt_check_adviser_code->num_rows > 0) {
            throw new Exception('Adviser code already exists for another user.');
        }
        $stmt_check_adviser_code->close();
    } else {
        throw new Exception('Failed to prepare statement for checking adviser code');
    }

    // Check if adviser code exists for the given user
    $sql_check_account = "SELECT adviser_code FROM useraccount WHERE UserID = ?";
    if ($stmt_check_account = $conn->prepare($sql_check_account)) {
        $stmt_check_account->bind_param("i", $user_id);
        $stmt_check_account->execute();
        $stmt_check_account->store_result();
        $stmt_check_account->bind_result($existing_adviser_code);
        $stmt_check_account->fetch();
        
        // Store num_rows before closing
        $has_account = $stmt_check_account->num_rows > 0;
        $stmt_check_account->close();

        // Log the existing adviser code
        // $response_messages[] = json_encode(["Step" => "Check existing adviser code", "Existing adviser code" => $existing_adviser_code, "Has account" => $has_account]);

        if ($has_account) {
            // Fetch and temporarily store submission codes
            $submission_codes = [];
            $sql_fetch_submissions = "SELECT id, submission_code FROM submission_status WHERE submission_code = ?";
            if ($stmt_fetch_submissions = $conn->prepare($sql_fetch_submissions)) {
                $stmt_fetch_submissions->bind_param("s", $existing_adviser_code);
                $stmt_fetch_submissions->execute();
                $stmt_fetch_submissions->bind_result($submission_id, $submission_code);
                while ($stmt_fetch_submissions->fetch()) {
                    $submission_codes[] = ['id' => $submission_id, 'code' => $submission_code];
                }
                $stmt_fetch_submissions->close();
            }

            // Log the fetched submission codes
            // $response_messages[] = json_encode(["Step" => "Fetch submission codes", "Fetched submission codes" => $submission_codes]);

            // Clear only the necessary submission_code values
            $sql_clear_submissions = "UPDATE submission_status SET submission_code = NULL WHERE submission_code = ?";
            if ($stmt_clear_submissions = $conn->prepare($sql_clear_submissions)) {
                $stmt_clear_submissions->bind_param("s", $existing_adviser_code);
                if (!$stmt_clear_submissions->execute()) {
                    throw new Exception('Failed to clear submission_status: ' . $stmt_clear_submissions->error);
                }
                $stmt_clear_submissions->close();
            }
        }

        // First update/insert useraccount (parent table)
        if ($has_account) {
            // Update useraccount
            $sql_update_account = "UPDATE useraccount SET adviser_code = ? WHERE UserID = ?";
            if ($stmt_update_account = $conn->prepare($sql_update_account)) {
                $stmt_update_account->bind_param("si", $adviser_code, $user_id);
                if (!$stmt_update_account->execute()) {
                    throw new Exception('Failed to update adviser code in useraccount: ' . $stmt_update_account->error);
                }
                $stmt_update_account->close();
                // $response_messages[] = 'Adviser code updated successfully in useraccount';
            }

            // Update userprofile where adviser_code matches
            $sql_update_profile = "UPDATE userprofile SET advisor_code = ? WHERE advisor_code = ?";
            if ($stmt_update_profile = $conn->prepare($sql_update_profile)) {
                $stmt_update_profile->bind_param("ss", $adviser_code, $existing_adviser_code);
                if (!$stmt_update_profile->execute()) {
                    throw new Exception('Failed to update adviser code in userprofile: ' . $stmt_update_profile->error);
                }
                $stmt_update_profile->close();
                // $response_messages[] = 'Adviser code updated successfully in userprofile';
            }
        } else {
            // Insert into useraccount
            $sql_insert_account = "INSERT INTO useraccount (UserID, adviser_code) VALUES (?, ?)";
            if ($stmt_insert_account = $conn->prepare($sql_insert_account)) {
                $stmt_insert_account->bind_param("is", $user_id, $adviser_code);
                if (!$stmt_insert_account->execute()) {
                    throw new Exception('Failed to insert adviser code in useraccount: ' . $stmt_insert_account->error);
                }
                $stmt_insert_account->close();
                // $response_messages[] = 'Adviser code inserted successfully in useraccount';
            }

            // Update userprofile where adviser_code matches
            $sql_update_profile = "UPDATE userprofile SET advisor_code = ? WHERE advisor_code = ?";
            if ($stmt_update_profile = $conn->prepare($sql_update_profile)) {
                $stmt_update_profile->bind_param("ss", $adviser_code, $existing_adviser_code);
                if (!$stmt_update_profile->execute()) {
                    throw new Exception('Failed to update adviser code in userprofile: ' . $stmt_update_profile->error);
                }
                $stmt_update_profile->close();
                // $response_messages[] = 'Adviser code updated successfully in userprofile';
            }
        }

        // Reassign the submission_status table (child table)
        if ($has_account) {
            foreach ($submission_codes as $entry) {
                $sql_update_submissions = "UPDATE submission_status SET submission_code = ? WHERE id = ?";
                if ($stmt_update_submissions = $conn->prepare($sql_update_submissions)) {
                    $stmt_update_submissions->bind_param("si", $adviser_code, $entry['id']);
                    if (!$stmt_update_submissions->execute()) {
                        throw new Exception('Failed to update submission_status: ' . $stmt_update_submissions->error);
                    }
                    $stmt_update_submissions->close();
                }
            }
        }
    } else {
        throw new Exception('Failed to prepare statement for checking useraccount');
    }
    $conn->commit();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $conn->rollback();
    // Delete the uploaded file if there is an error
    if (!empty($uploaded_file_path) && file_exists($uploaded_file_path)) {
        unlink($uploaded_file_path);
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit();
}

// Close the database connection
$conn->close();
?>