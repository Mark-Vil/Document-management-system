<?php
require '../../vendor/autoload.php';
require 'dbconnection.php'; // Ensure this path is correct

use TeamTNT\TNTSearch\TNTSearch;

header('Content-Type: application/json');

if (isset($_GET['search']) && isset($_GET['department_code']) && isset($_GET['year'])) {
    $searchTerm = $_GET['search'];
    $departmentCode = $_GET['department_code'];
    $year = $_GET['year'];

    // Log the search term for debugging
    error_log("Search term: " . $searchTerm);
    error_log("Department code: " . $departmentCode);
    error_log("Year: " . $year);

    $tnt = new TNTSearch();
    $tnt->loadConfig([
        'driver'  => 'sqlite',
        'storage' => __DIR__.'/../../indexes', // Ensure the path is correct
    ]);

    // Enable fuzzy search
    $tnt->setFuzziness(true);
    $fuzzy_prefix_length  = 1;
    $fuzzy_max_expansions = 100;
    $fuzzy_distance       = 2; 

    try {
        $tnt->selectIndex('archive.index');
        $results = $tnt->search($searchTerm, 10); // Limit to 10 results

        // Log the search results for debugging
        error_log("Search results: " . print_r($results, true));

        // Fetch the actual data from the database based on the search results
        if (!empty($results['ids'])) {
            $ids = implode(',', $results['ids']);
            $query = "
                SELECT 
                    a.id, 
                    a.research_title AS title, 
                    ua.email AS author_email, 
                    ss.dateofsubmission, 
                    ss.date_accepted, 
                    ss.status 
                FROM 
                    archive a
                JOIN 
                    submission_status ss ON a.id = ss.submission_id
                JOIN 
                    useraccount ua ON a.UserID = ua.UserID
                JOIN 
                    departments d ON a.faculty_code = d.department_code
                WHERE 
                    a.id IN ($ids) AND 
                    d.department_code = ? AND 
                    YEAR(ss.dateofsubmission) = ?
            ";

            // Log the query for debugging
            error_log("Database query: " . $query);

            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $departmentCode, $year);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $articles = $result->fetch_all(MYSQLI_ASSOC);
                // Log the fetched articles for debugging
                error_log("Fetched articles: " . print_r($articles, true));

                // Sort the articles to prioritize exact matches and more relevant results
                usort($articles, function($a, $b) use ($searchTerm) {
                    $exactMatchA = stripos($a['title'], $searchTerm) === 0;
                    $exactMatchB = stripos($b['title'], $searchTerm) === 0;
                    if ($exactMatchA && !$exactMatchB) {
                        return -1;
                    } elseif (!$exactMatchA && $exactMatchB) {
                        return 1;
                    } else {
                        // Further sorting by relevance
                        return levenshtein($a['title'], $searchTerm) - levenshtein($b['title'], $searchTerm);
                    }
                });
            } else {
                $articles = [];
                error_log("Database query failed: " . $conn->error);
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
    echo json_encode(['error' => 'No search term or department code or year provided']);
}
?>