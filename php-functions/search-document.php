<?php
require '../vendor/autoload.php';
require 'dbconnection.php';

use TeamTNT\TNTSearch\TNTSearch;

header('Content-Type: application/json');

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $collegeCode = $_GET['college_code'];
    $departmentName = $_GET['department_name'];
    $year = $_GET['year'];

    error_log("Search term: " . $searchTerm);
    error_log("Filters - College: $collegeCode, Department: $departmentName, Year: $year");

    $tnt = new TNTSearch();
    $tnt->loadConfig([
        'driver'  => 'mysql',
        'storage' => __DIR__.'/../indexes',
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
        WHERE (a.id IN ($ids) OR " . ($isYearSearch ? "YEAR(ss.dateofsubmission) = " . $searchTerm : "FALSE") . ")
        AND ss.status IN ('Accepted', 'Locked')
    ";

            if (!empty($collegeCode)) {
                $query .= " AND c.college_code = ?";
            }
            if (!empty($departmentName)) {
                $query .= " AND d.department_name = ?";
            }
            if (!empty($year) && $year !== $searchTerm) {
                $query .= " AND YEAR(ss.dateofsubmission) = ?";
            }

            $stmt = $conn->prepare($query);
            
            $bindTypes = '';
            $bindValues = [];
            if (!empty($collegeCode)) {
                $bindTypes .= 's';
                $bindValues[] = $collegeCode;
            }
            if (!empty($departmentName)) {
                $bindTypes .= 's';
                $bindValues[] = $departmentName;
            }
            if (!empty($year)) {
                $bindTypes .= 's';
                $bindValues[] = $year;
            }

            if (!empty($bindValues)) {
                $stmt->bind_param($bindTypes, ...$bindValues);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $articles = $result->fetch_all(MYSQLI_ASSOC);
                
                foreach ($articles as &$article) {
                    $searchWords = explode(' ', strtolower($searchTerm));
                    $count = 0;
                    $fuzzyScore = 0;
                    $isYearSearch = preg_match('/^[0-9]{4}$/', $searchTerm);
                    
                    // Year-specific scoring
                    if ($isYearSearch) {
                        $submissionYear = date('Y', strtotime($article['dateofsubmission']));
                        if ($submissionYear === $searchTerm) {
                            $count += 5; // Higher weight for exact year match
                        }
                    }
                    
                    // Enhanced fuzzy matching
                    foreach ($searchWords as $word) {
                        $titleWords = explode(' ', strtolower($article['research_title']));
                        $keywordWords = explode(',', strtolower($article['keywords']));
                        $abstractWords = explode(' ', strtolower($article['abstract']));
                        $authorWords = explode(' ', strtolower($article['author']));
                        
                        foreach (array_merge($titleWords, $keywordWords, $abstractWords, $authorWords) as $target) {
                            $target = trim($target);
                            if (strlen($target) > 0) {
                                $distance = levenshtein($word, $target);
                                if ($distance <= 2) {
                                    $fuzzyScore += (3 - $distance);
                                }
                            }
                        }
                        
                        // Regular word matching (skip for year search)
                        if (!$isYearSearch) {
                            $count += substr_count(strtolower($article['research_title']), $word);
                            $count += substr_count(strtolower($article['abstract']), $word);
                            $count += substr_count(strtolower($article['author']), $word);
                            $count += substr_count(strtolower($article['keywords']), $word);
                        }
                    }
                
                    $article['match_count'] = $count;
                    $article['fuzzy_score'] = $fuzzyScore;
                    $article['total_score'] = $isYearSearch ? $count : ($count + ($fuzzyScore * 0.2));
                    
                    error_log("Score - Title: {$article['research_title']}, Count: $count, Fuzzy: $fuzzyScore, Total: {$article['total_score']}");
                }

                // Remove duplicates and sort
                $uniqueArticles = array_values(array_unique($articles, SORT_REGULAR));
                usort($uniqueArticles, function($a, $b) {
                    if ($b['total_score'] !== $a['total_score']) {
                        return $b['total_score'] - $a['total_score'];
                    }
                    return strtotime($b['dateofsubmission']) - strtotime($a['dateofsubmission']);
                });

                $articles = array_values(array_filter($uniqueArticles, function($article) {
                    return $article['total_score'] > 0;
                }));
            }
        }

        echo json_encode($articles ?? []);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No search term provided']);
}
?>