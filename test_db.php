<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

echo "<h2>Testing Database Connection</h2>";

try {
    // Check if tables exist
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Found tables: " . implode(", ", $tables) . "</p>";
    
    // Check animals table structure
    $columns = $pdo->query("DESCRIBE animals")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Animals Table Structure:</h3>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // Count animals in the database
    $count = $pdo->query("SELECT COUNT(*) as count FROM animals")->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total animals in database: " . $count['count'] . "</p>";
    
    // Get all animals
    $animals = $pdo->query("SELECT * FROM animals")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>All Animals:</h3>";
    echo "<pre>";
    print_r($animals);
    echo "</pre>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 