<?php
require '../vendor/autoload.php';
require 'dbconnection.php'; // Ensure this path is correct

use TeamTNT\TNTSearch\TNTSearch;

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $currentDocumentId = $_GET['id']; // Get the current document ID from the AJAX request

    // Fetch current document title and keywords
    $sql = "SELECT research_title, keywords FROM archive WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $currentDocumentId);
    $stmt->execute();
    $stmt->bind_result($research_title, $keywords);
    $stmt->fetch();
    $stmt->close();

    if ($research_title || $keywords) {
        // Normalize both title and keywords
        $normalizedKeywords = normalizeKeywords($keywords);
        $normalizedTitle = strtolower(trim($research_title));

        // Combine title and keywords
        $combinedSearchTerm = $normalizedTitle . ' ' . implode(' ', $normalizedKeywords);

        $tnt = new TNTSearch();
        $tnt->loadConfig([
            'driver'    => 'mysql',
            'host'      => 'your_db_host',
            'database'  => 'your_db_name',
            'username'  => 'your_db_user',
            'password'  => 'your_db_password',
            'storage'   => __DIR__.'/../indexes', // Ensure the path is correct
        ]);

        $tnt->setFuzziness(true);
        
        // Using array to configure fuzzy settings
        $tnt->setFuzziness(true);
        $fuzzy_prefix_length  = 2;
        $fuzzy_max_expansions = 100;
        $fuzzy_distance       = 3; 
        
        try {
            $tnt->selectIndex('archive.index');
            $results = $tnt->search($combinedSearchTerm, 10); // Limit to 10 results

            if (!empty($results['ids'])) {
                // Filter out the current document ID
                $filteredIds = array_diff($results['ids'], [$currentDocumentId]);
                
                if (!empty($filteredIds)) {
                    $ids = implode(',', $filteredIds);
                    $query = "
                        SELECT a.id, a.research_title, a.abstract 
                        FROM archive a
                        JOIN submission_status ss ON a.id = ss.submission_id
                        WHERE a.id IN ($ids) AND ss.status IN ('Accepted', 'Locked')
                    ";

                    $stmt = $conn->query($query);

                    if ($stmt) {
                        $articles = $stmt->fetch_all(MYSQLI_ASSOC);
                        
                        // Sort the articles to prioritize exact matches
                        usort($articles, function($a, $b) use ($combinedSearchTerm) {
                            $exactMatchA = stripos($a['research_title'], $combinedSearchTerm) === 0;
                            $exactMatchB = stripos($b['research_title'], $combinedSearchTerm) === 0;
                            if ($exactMatchA && !$exactMatchB) {
                                return -1;
                            } elseif (!$exactMatchA && $exactMatchB) {
                                return 1;
                            } else {
                                return 0;
                            }
                        });

                        echo json_encode($articles);
                    } else {
                        echo json_encode([]);
                    }
                } else {
                    echo json_encode([]);
                }
            } else {
                echo json_encode([]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}

function normalizeKeywords($keywords) {
    $keywordsArray = array_map('trim', explode(',', $keywords));
    return array_map('strtolower', $keywordsArray);
}
?>
