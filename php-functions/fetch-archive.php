<?php
include 'dbconnection.php';

function encrypt($data) {
    return base64_encode($data);
}

$collegeCode = isset($_GET['college_code']) ? $_GET['college_code'] : '';
$departmentName = isset($_GET['department_name']) ? $_GET['department_name'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 6; // Set limit to 1 to display 1 card per page
$offset = ($page - 1) * $limit;

try {
    $sql = "
        SELECT 
            a.id,
            a.research_title,
            a.author,
            a.co_authors,
            a.abstract,
            a.keywords,
            a.file_path,
            ss.date_accepted,
            d.department_name,
            c.college_name
        FROM 
            archive a
        JOIN 
            submission_status ss ON a.id = ss.submission_id
        JOIN 
            departments d ON a.faculty_code = d.department_code
        JOIN 
            colleges c ON d.college_code = c.college_code
        WHERE 
            ss.status IN ('Accepted', 'Locked')
    ";

    if ($collegeCode) {
        $sql .= " AND c.college_code = ?";
    }
    if ($departmentName) {
        $sql .= " AND d.department_name = ?";
    }
    if ($year) {
        $sql .= " AND YEAR(ss.date_accepted) = ?";
    }

    $sql .= " ORDER BY ss.date_accepted DESC LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);

    $params = [];
    $types = '';

    if ($collegeCode) {
        $params[] = $collegeCode;
        $types .= 's';
    }
    if ($departmentName) {
        $params[] = $departmentName;
        $types .= 's';
    }
    if ($year) {
        $params[] = $year;
        $types .= 'i';
    }
    $params[] = $limit;
    $types .= 'i';
    $params[] = $offset;
    $types .= 'i';

    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $archives = $result->fetch_all(MYSQLI_ASSOC);

    // Encrypt the IDs
    foreach ($archives as &$archive) {
        $archive['encrypted_id'] = encrypt($archive['id']);
    }

    // Fetch total count for pagination
    $countSql = "
        SELECT COUNT(*) as total
        FROM 
            archive a
        JOIN 
            submission_status ss ON a.id = ss.submission_id
        JOIN 
            departments d ON a.faculty_code = d.department_code
        JOIN 
            colleges c ON d.college_code = c.college_code
        WHERE 
            ss.status IN ('Accepted', 'Locked')
    ";

    $countParams = [];
    $countTypes = '';

    if ($collegeCode) {
        $countSql .= " AND c.college_code = ?";
        $countParams[] = $collegeCode;
        $countTypes .= 's';
    }
    if ($departmentName) {
        $countSql .= " AND d.department_name = ?";
        $countParams[] = $departmentName;
        $countTypes .= 's';
    }
    if ($year) {
        $countSql .= " AND YEAR(ss.date_accepted) = ?";
        $countParams[] = $year;
        $countTypes .= 'i';
    }

    $countStmt = $conn->prepare($countSql);

    if ($countParams) {
        $countStmt->bind_param($countTypes, ...$countParams);
    }

    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalCount = $countResult->fetch_assoc()['total'];

    echo json_encode(['archives' => $archives, 'total' => $totalCount]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['error' => htmlspecialchars($e->getMessage())]);
}
?>