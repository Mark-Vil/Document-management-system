<?php
require '../../vendor/autoload.php';
require 'dbconnection.php';

use TeamTNT\TNTSearch\TNTSearch;

header('Content-Type: application/json');



if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $collegeCode = isset($_GET['college_code']) ? $_GET['college_code'] : '';

    error_log("Search term: " . $searchTerm);


    $tnt = new TNTSearch();
    $tnt->loadConfig([
        'driver'  => 'mysql',
        'storage' => __DIR__.'/../../indexes',
    ]);

    $tnt->setFuzziness(true);
    $fuzzy_prefix_length = 2;
    $fuzzy_max_expansions = 100;
    $fuzzy_distance = 3;

    try {
        $tnt->selectIndex('archive.index');
        $results = $tnt->search($searchTerm, 20);
        error_log("TNTSearch results: " . print_r($results, true));

        if (!empty($results['ids'])) {
            $ids = implode(',', $results['ids']);
            $isYearSearch = preg_match('/^[0-9]{4}$/', $searchTerm);
            
            $query = "
                SELECT a.id, a.research_title, a.author, a.co_authors, a.abstract, a.keywords, 
                       ss.dateofsubmission, c.college_name, d.department_name
                FROM archive a
                JOIN submission_status ss ON a.id = ss.submission_id
                JOIN departments d ON a.faculty_code = d.department_code
                JOIN colleges c ON d.college_code = c.college_code
                WHERE a.id IN ($ids) 
                AND ss.status IN ('Accepted', 'Locked')
            ";

            $params = array();
            $types = "";

            if (!empty($collegeCode)) {
                $query .= " AND c.college_code = ?";
                $params[] = $collegeCode;
                $types .= "s";
            }
            
            // Log the query for debugging
            error_log("Database query: " . $query);

            
            $stmt = $conn->prepare($query);
            
            $bindTypes = '';
            $bindValues = [];
            if (!empty($collegeCode)) {
                $bindTypes .= 's';
                $bindValues[] = $collegeCode;
            }

            if (!empty($bindValues)) {
                $stmt->bind_param($bindTypes, ...$bindValues);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $articles = $result->fetch_all(MYSQLI_ASSOC);
            
                // Check if search term is a year
                $isYearSearch = preg_match('/^[0-9]{4}$/', $searchTerm);
                
                foreach ($articles as &$article) {
                    $searchWords = explode(' ', strtolower($searchTerm));
                    $count = 0;
                    $fuzzyScore = 0;

                    foreach ($searchWords as $word) {
    // Apply fuzzy matching with weighted Levenshtein distance
    $titleWords = explode(' ', strtolower($article['research_title']));
    $keywordWords = explode(',', strtolower($article['keywords']));
    $abstractWords = explode(' ', strtolower($article['abstract']));
    $authorWords = explode(' ', strtolower($article['author']));
    
    // Check title (highest weight)
    foreach ($titleWords as $target) {
        $target = trim($target);
        if (strlen($target) > 0) {
            $distance = levenshtein($word, $target);
            if ($distance <= 2) {
                $fuzzyScore += (3 - $distance) * 2; // Double weight for title
            }
        }
    }

    // Check abstract (normal weight)
    foreach ($abstractWords as $target) {
        $target = trim($target);
        if (strlen($target) > 0) {
            $distance = levenshtein($word, $target);
            if ($distance <= 2) {
                $fuzzyScore += (3 - $distance);  // Normal weight
            }
        }
    }
    
    
    // Check keywords (high weight)
    foreach ($keywordWords as $target) {
        $target = trim($target);
        if (strlen($target) > 0) {
            $distance = levenshtein($word, $target);
            if ($distance <= 2) {
                $fuzzyScore += (3 - $distance) * 1.5; // 1.5x weight for keywords
            }
        }
    }
    
    
    // Check author (high weight)
    foreach ($authorWords as $target) {
        $target = trim($target);
        if (strlen($target) > 0) {
            $distance = levenshtein($word, $target);
            if ($distance <= 2) {
                $fuzzyScore += (3 - $distance); // weight importance
            }
        }
    }
}
                    
                    // Handle year search
                    if ($isYearSearch) {
                        $submissionYear = date('Y', strtotime($article['dateofsubmission']));
                        if ($submissionYear === $searchTerm) {
                            $count += 1;
                        }
                    }
                    
                    // Regular word matching (keep existing logic)
                    foreach ($searchWords as $word) {
                        // ...existing keyword matching code...
                        $keywordsArray = array_map('trim', explode(',', strtolower($article['keywords'])));
                        foreach ($keywordsArray as $keyword) {
                            if (strpos($keyword, $word) !== false) {
                                $count++;
                            }
                        }
                        
                        // Title and abstract matching
                        $count += substr_count(strtolower($article['research_title']), $word);
                        $count += substr_count(strtolower($article['abstract']), $word);
                        $count += substr_count(strtolower($article['author']), $word);
                        
                        if (!$isYearSearch) {
                            $count += substr_count(strtolower($article['dateofsubmission']), $word);
                        }
                    }
            
                    // Combine exact match count and fuzzy score
                    $article['match_count'] = $count;
                    $article['fuzzy_score'] = $fuzzyScore;
                    $article['total_score'] = $count + ($fuzzyScore * 0.2);
                    
                    error_log("Title: {$article['research_title']}, Count: {$count}, Fuzzy: {$fuzzyScore}, Total: {$article['total_score']}");
                }
                
                // Remove duplicates
                $uniqueArticles = array_values(array_unique($articles, SORT_REGULAR));
                
                // Sort by total score and date
                // Sort by total score then by date
usort($uniqueArticles, function($a, $b) {
    $scoreDiff = $b['total_score'] - $a['total_score'];
    if (abs($scoreDiff) > 0.0001) {
        return $scoreDiff > 0 ? 1 : -1;
    }
    return strtotime($b['dateofsubmission']) - strtotime($a['dateofsubmission']);
});
                
                $uniqueArticles = array_filter($uniqueArticles, function($article) {
                    return $article['total_score'] > 0;
                });
                
                $articles = array_values($uniqueArticles);
            }
        } else {
            $articles = [];
            error_log("No IDs found in search results.");
        }

        echo json_encode($articles);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No search term provided']);
}
?>