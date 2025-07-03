<?php
require_once 'config/database.php';
require_once 'classes/Animal.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $animal = new Animal($pdo);
    $animals = $animal->getAll();
    
    echo "<h2>Animals in Database:</h2>";
    echo "<pre>";
    print_r($animals);
    echo "</pre>";
    
    echo "<h2>Raw Query Results:</h2>";
    $rawResults = $pdo->query("SELECT * FROM animals")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($rawResults);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "<pre>";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
    echo "</pre>";
}
?> 