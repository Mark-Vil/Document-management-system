<?php
include 'dbconnection.php';

function generateUniqueCollegeCode($conn) {
    do {
        // Generate 8 digit random number
        $code = str_pad(mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        
        // Check if code exists
        $stmt = $conn->prepare("SELECT college_code FROM colleges WHERE college_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
    } while($exists);
    
    return $code;
}

// Get form data
$collegeName = $_POST['collegeName'];
// Generate unique college code
$collegeCode = generateUniqueCollegeCode($conn);

// Capitalize the first letter of each word in the college name
$collegeName = ucwords(strtolower($collegeName));

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO colleges (college_name, college_code) VALUES (?, ?)");
$stmt->bind_param("si", $collegeName, $collegeCode);

// Execute the statement
$response = array();
try {
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'New record created successfully';
    } else {
        throw new Exception($stmt->error, $stmt->errno);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        $response['status'] = 'error';
        $response['message'] = 'Duplicate entry: A record with this college code already exists.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>