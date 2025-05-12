<?php
// Dummy data without database connection
// $dummyData = [
//     [
//         'title' => 'Data Mining Using Natural Language Processing',
//         'downloads' => 45,
//         'views' => 120,
//         'citations' => 15
//     ],
//     [
//         'title' => 'Machine Learning in Healthcare',
//         'downloads' => 32,
//         'views' => 89,
//         'citations' => 8
//     ],
//     [
//         'title' => 'Blockchain Technology Implementation',
//         'downloads' => 67,
//         'views' => 234,
//         'citations' => 23
//     ]
// ];

// echo json_encode($dummyData);
include 'dbconnection.php';
session_start();

if (!isset($_SESSION['faculty_id'])) {
    echo json_encode(['error' => 'No session found']);
    exit();
}

$student_id = $_SESSION['faculty_id'];

$sql = "
    SELECT 
        a.id as research_id,
        a.research_title,
        COUNT(DISTINCT d.id) as total_downloads,
        COUNT(DISTINCT v.id) as total_views,
        COUNT(DISTINCT c.id) as total_citations
    FROM archive a
    LEFT JOIN downloads d ON a.id = d.research_id
    LEFT JOIN views v ON a.id = v.research_id
    LEFT JOIN citation c ON a.id = c.research_id
    WHERE a.UserID = ?
    GROUP BY a.id, a.research_title
    ORDER BY a.research_title";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'title' => $row['research_title'],
            'downloads' => (int)$row['total_downloads'],
            'views' => (int)$row['total_views'],
            'citations' => (int)$row['total_citations']
        );
    }
    
    echo json_encode($data);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Query failed']);
}

$conn->close();
?>