<?php
require '../vendor/autoload.php';
require 'dbconnection.php';

function normalizeWords($text) {
    $words = preg_split('/[,\s]+/', $text);
    return array_filter(array_map('strtolower', array_map('trim', $words)));
}

header('Content-Type: application/json');

if (!isset($_GET['research_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No research ID provided',
        'data' => []
    ]);
    exit;
}

$currentDocumentId = $_GET['research_id'];
$similarities = [];
try {
    // First, let's get all potential matches without status filtering
    $sql = "
        SELECT 
            a.id,
            a.research_title,
            a.keywords,
            ss.status
        FROM 
            archive a
            LEFT JOIN submission_status ss ON a.id = ss.submission_id
        WHERE 
            a.id != ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $currentDocumentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Get current document's keywords and normalize
    $currentDoc = $conn->prepare("SELECT keywords, research_title FROM archive WHERE id = ?");
    $currentDoc->bind_param("i", $currentDocumentId);
    $currentDoc->execute();
    $currentDoc->bind_result($currentKeywords, $currentDocTitle);
    $currentDoc->fetch();
    $currentDoc->close();

    // Process current document words
    $currentTitle = normalizeWords($currentDocTitle);
    $currentKeywordsArray = normalizeWords($currentKeywords);
    $currentWords = array_unique(array_merge($currentTitle, $currentKeywordsArray));

    while ($row = $result->fetch_assoc()) {
        $compareWords = array_unique(array_merge(
            normalizeWords($row['research_title']),
            normalizeWords($row['keywords'])
        ));
        
        $commonWords = array_intersect($currentWords, $compareWords);
        $allWords = array_unique(array_merge($currentWords, $compareWords));
        
        if (!empty($allWords)) {
            $similarity = count($commonWords) / count($allWords);
            if ($similarity > 0.05) {
                $similarities[$row['id']] = $similarity;
            }
        }
    }
    
    // Sort by similarity
    arsort($similarities);
    
    if (!empty($similarities)) {
        arsort($similarities);
        $similarIds = array_keys($similarities);
        $placeholders = str_repeat('?,', count($similarIds) - 1) . '?';
        
        $query = "SELECT a.id, a.research_title, a.abstract 
                FROM archive a
                JOIN submission_status ss ON a.id = ss.submission_id
                WHERE a.id IN ($placeholders) 
                AND ss.status IN ('Accepted', 'Locked')";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('i', count($similarIds)), ...$similarIds);
        $stmt->execute();
        $finalResults = $stmt->get_result();
        
        $articles = [];
        while ($row = $finalResults->fetch_assoc()) {
            $articles[] = $row;
        }
        
        echo json_encode($articles);
    } else {
        echo json_encode([]);
    }

} catch (Exception $e) {
    echo json_encode([]);
}

function normalizeKeywords($keywords) {
    // Split by comma and space
    $words = preg_split('/[,\s]+/', $keywords);
    return array_filter(array_map('strtolower', array_map('trim', $words)));
}
?>