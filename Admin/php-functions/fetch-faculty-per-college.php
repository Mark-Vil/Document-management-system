<?php
// header('Content-Type: application/json');

// $data = [
//     [
//         'college_name' => 'College of Computing Studies',
//         'departments' => [
//             'Computer Science',
//             'Information Technology'
//         ],
//         'department_count' => 2
//     ],
//     [
//         'college_name' => 'College of Engineering',
//         'departments' => [
//             'Civil Engineering',
//             'Mechanical Engineering',
//             'Electrical Engineering'
//         ],
//         'department_count' => 3
//     ],
//     [
//         'college_name' => 'College of Business Administration',
//         'departments' => [
//             'Accounting',
//             'Marketing',
//             'Finance'
//         ],
//         'department_count' => 3
//     ],
//     [
//         'college_name' => 'College of Arts and Sciences',
//         'departments' => [
//             'Biology',
//             'Chemistry',
//             'Physics',
//             'Mathematics'
//         ],
//         'department_count' => 4
//     ]
// ];

// echo json_encode(['status' => 'success', 'data' => $data]);

include 'dbconnection.php';
session_start();

try {
    $sql = "
        SELECT c.college_name, d.department_name
        FROM colleges c
        JOIN departments d ON c.college_code = d.college_code
    ";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $college_name = $row['college_name'];
        $department_name = $row['department_name'];

        if (!isset($data[$college_name])) {
            $data[$college_name] = [
                'college_name' => $college_name,
                'departments' => [],
                'department_count' => 0
            ];
        }

        $data[$college_name]['departments'][] = $department_name;
        $data[$college_name]['department_count']++;
    }

    // Reindex array to remove college_name keys
    $data = array_values($data);

    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>