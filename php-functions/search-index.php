<?php
require '../vendor/autoload.php';
require 'dbconnection.php'; // Ensure this path is correct

use TeamTNT\TNTSearch\TNTSearch;

$tnt = new TNTSearch();
$tnt->loadConfig([
    'driver'   => 'mysql',
    'host'     => 'localhost', // Replace with your MySQL host
    'database' => 'rmis', // Replace with your MySQL database name
    'username' => 'root', // Replace with your MySQL username
    'password' => '', // Replace with your MySQL password
    'storage'  => __DIR__.'/../indexes', // Adjust the storage path
]);

$indexer = $tnt->createIndex('archive.index');
$indexer->query('SELECT id, research_title, author, abstract, keywords FROM archive'); // Adjust the query to match your database structure
$indexer->run();

echo "Index created successfully.";
?>