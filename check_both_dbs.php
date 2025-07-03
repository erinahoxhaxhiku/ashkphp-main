<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function checkDatabase($dbname) {
    echo "<h1>Checking database: $dbname</h1>";
    
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=$dbname", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get all tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "<h2>Tables in $dbname:</h2>";
        echo "<pre>";
        print_r($tables);
        echo "</pre>";
        
        // Check for animals table
        if (in_array('animals', $tables)) {
            echo "<h3>Animals table structure:</h3>";
            $structure = $pdo->query("DESCRIBE animals")->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>";
            print_r($structure);
            echo "</pre>";
            
            echo "<h3>Sample data from animals table:</h3>";
            $data = $pdo->query("SELECT * FROM animals")->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            
            echo "<h3>Count of animals:</h3>";
            $count = $pdo->query("SELECT COUNT(*) FROM animals")->fetchColumn();
            echo "<p>Total animals: $count</p>";
        } else {
            echo "<p>No animals table found in this database.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<h2>Error:</h2>";
        echo "<pre>";
        echo "Message: " . $e->getMessage() . "\n";
        echo "</pre>";
    }
}

// Check both databases
checkDatabase('animal_shelter');
echo "<hr>";
checkDatabase('animalshelter');
?> 