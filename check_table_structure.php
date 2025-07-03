<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

try {
    // Get table structure
    $sql = "DESCRIBE adoption_applications;";
    $stmt = $pdo->query($sql);
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Table structure for adoption_applications:\n";
    foreach ($columns as $column) {
        echo "Column: " . $column['Field'] . " | Type: " . $column['Type'] . "\n";
    }
} catch (PDOException $e) {
    die("Error checking table structure: " . $e->getMessage());
} 