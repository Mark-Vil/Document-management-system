<?php
require '../vendor/autoload.php';
require 'dbconnection.php';

use TeamTNT\TNTSearch\TNTSearch; // Import TNTSearch class

// Set response type to JSON for API output
header('Content-Type: application/json');

// Check if a search term is provided in the GET request
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search']; // The main search query
    $collegeCode = isset($_GET['college_code']) ? $_GET['college_code'] : ''; // Optional college filter
    $departmentName = isset($_GET['department_name']) ? $_GET['department_name'] : ''; // Optional department filter
    $year = isset($_GET['year']) ? $_GET['year'] : ''; // Optional year filter

    error_log("Search term: " . $searchTerm);
    error_log("Filters - College: $collegeCode, Department: $departmentName, Year: $year");

    // Initialize TNTSearch for full-text search
    $tnt = new TNTSearch();
    $tnt->loadConfig([
        'driver'  => 'mysql', // Use MySQL as the backend
        'storage' => __DIR__.'/../indexes', // Path to TNTSearch index files
    ]);

    // Enable fuzzy search for typo tolerance
    $tnt->setFuzziness(true);
    $fuzzy_prefix_length = 2; 
    $fuzzy_max_expansions = 100;
    $fuzzy_distance = 3;

    try {
        // Select the pre-built TNTSearch index for the archive table
        $tnt->selectIndex('archive.index');
        // Perform the search, retrieving up to 20 results
        $results = $tnt->search($searchTerm, 20);
        error_log("TNTSearch results: " . print_r($results, true));

        if (!empty($results['ids'])) {
            $ids = implode(',', $results['ids']); // List of matching archive IDs
            $isYearSearch = preg_match('/^[0-9]{4}$/', $searchTerm); // Detect if search is a year
            
            // Build SQL query to fetch article details, joining related tables
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

            $params = array();
            $types = "";

            // Add optional filters to the query
            if (!empty($collegeCode)) {
                $query .= " AND c.college_code = ?";
                $params[] = $collegeCode;
                $types .= "s";
            }
            if (!empty($departmentName)) {
                $query .= " AND d.department_name = ?";
                $params[] = $departmentName;
                $types .= "s";
            }
            if (!empty($year) && $year !== $searchTerm) {
                $query .= " AND YEAR(ss.dateofsubmission) = ?";
                $params[] = $year;
                $types .= "s";
            }
            

            error_log("Database query: " . $query);

            // Prepare and bind parameters for the SQL query
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

                $isYearSearch = preg_match('/^[0-9]{4}$/', $searchTerm);
                
                // For each article, calculate match and fuzzy scores
                foreach ($articles as &$article) {
                    $searchWords = explode(' ', strtolower($searchTerm)); // Split search into words
                    $count = 0; // Exact match count
                    $fuzzyScore = 0; // Fuzzy match score

                    foreach ($searchWords as $word) {
                        // --- Fuzzy matching using Levenshtein distance ---
                        // Tokenize fields for comparison
                        $titleWords = explode(' ', strtolower($article['research_title']));
                        $keywordWords = explode(',', strtolower($article['keywords']));
                        $abstractWords = explode(' ', strtolower($article['abstract']));
                        $authorWords = explode(' ', strtolower($article['author']));
                        
                        // Title: highest weight for close matches
                        foreach ($titleWords as $target) {
                            $target = trim($target);
                            if (strlen($target) > 0) {
                                $distance = levenshtein($word, $target);
                                if ($distance <= 2) {
                                    $fuzzyScore += (3 - $distance) * 2;
                                }
                            }
                        }
                        // Abstract: normal weight
                        foreach ($abstractWords as $target) {
                            $target = trim($target);
                            if (strlen($target) > 0) {
                                $distance = levenshtein($word, $target);
                                if ($distance <= 2) {
                                    $fuzzyScore += (3 - $distance);
                                }
                            }
                        }
                        // Keywords: high weight
                        foreach ($keywordWords as $target) {
                            $target = trim($target);
                            if (strlen($target) > 0) {
                                $distance = levenshtein($word, $target);
                                if ($distance <= 2) {
                                    $fuzzyScore += (3 - $distance) * 1.5;
                                }
                            }
                        }
                        // Author: high weight
                        foreach ($authorWords as $target) {
                            $target = trim($target);
                            if (strlen($target) > 0) {
                                $distance = levenshtein($word, $target);
                                if ($distance <= 2) {
                                    $fuzzyScore += (3 - $distance);
                                }
                            }
                        }
                    }
                    // --- End fuzzy matching ---
                    
                    // If searching by year, increment count if year matches
                    if ($isYearSearch) {
                        $submissionYear = date('Y', strtotime($article['dateofsubmission']));
                        if ($submissionYear === $searchTerm) {
                            $count += 1;
                        }
                    }
                    
                    // --- Exact word matching ---
                    foreach ($searchWords as $word) {
                        $keywordsArray = array_map('trim', explode(',', strtolower($article['keywords'])));
                        foreach ($keywordsArray as $keyword) {
                            if (strpos($keyword, $word) !== false) {
                                $count++;
                            }
                        }
                        // Count occurrences in title, abstract, author, and date
                        $count += substr_count(strtolower($article['research_title']), $word);
                        $count += substr_count(strtolower($article['abstract']), $word);
                        $count += substr_count(strtolower($article['author']), $word);
                        if (!$isYearSearch) {
                            $count += substr_count(strtolower($article['dateofsubmission']), $word);
                        }
                    }
                    // --- End exact word matching ---
            
                    // Combine exact match count and fuzzy score for ranking
                    $article['match_count'] = $count;
                    $article['fuzzy_score'] = $fuzzyScore;
                    $article['total_score'] = $count + ($fuzzyScore * 0.2); // Weighted sum
                    
                    error_log("Title: {$article['research_title']}, Count: {$count}, Fuzzy: {$fuzzyScore}, Total: {$article['total_score']}");
                }
                
                // Remove duplicate articles
                $uniqueArticles = array_values(array_unique($articles, SORT_REGULAR));
                
                // --- Sorting technique ---
                // Sort by total_score (relevance), then by date (most recent first)
                // Note: TNTSearch internally uses BM25/TF-IDF for its ranking.
                usort($uniqueArticles, function($a, $b) {
                    $scoreDiff = $b['total_score'] - $a['total_score'];
                    if (abs($scoreDiff) > 0.0001) {
                        return $scoreDiff > 0 ? 1 : -1;
                    }
                    return strtotime($b['dateofsubmission']) - strtotime($a['dateofsubmission']);
                });
                // --- End sorting ---
                
                // Filter out articles with zero score
                $uniqueArticles = array_filter($uniqueArticles, function($article) {
                    return $article['total_score'] > 0;
                });
                
                $articles = array_values($uniqueArticles);
            }
        } else {
            $articles = [];
            error_log("No IDs found in search results.");
        }

        // Return the final sorted and filtered articles as JSON
        echo json_encode($articles);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // No search term provided in the request
    echo json_encode(['error' => 'No search term provided']);
}
?>