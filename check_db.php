<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // First try to connect without specifying a database
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h2>Available Databases:</h2>";
    echo "<pre>";
    print_r($databases);
    echo "</pre>";
    
    if (!in_array('animal_shelter', $databases)) {
        echo "<p>animal_shelter database does not exist! Creating it...</p>";
        $pdo->exec("CREATE DATABASE animal_shelter");
        echo "<p>Database created successfully!</p>";
    }
    
    // Connect to the animal_shelter database
    $pdo = new PDO("mysql:host=localhost;dbname=animal_shelter", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check existing tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h2>Existing Tables:</h2>";
    echo "<pre>";
    print_r($tables);
    echo "</pre>";
    
    // Create tables if they don't exist
    if (!in_array('animals', $tables)) {
        echo "<p>Creating animals table...</p>";
        $sql = "CREATE TABLE animals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            species VARCHAR(50) NOT NULL,
            description TEXT,
            status ENUM('available', 'pending', 'adopted') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
        echo "<p>Animals table created successfully!</p>";
    }
    
    if (!in_array('images', $tables)) {
        echo "<p>Creating images table...</p>";
        $sql = "CREATE TABLE images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            animal_id INT,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
        )";
        $pdo->exec($sql);
        echo "<p>Images table created successfully!</p>";
    }
    
    // Check table structures
    foreach ($tables as $table) {
        echo "<h3>Structure of $table table:</h3>";
        $structure = $pdo->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($structure);
        echo "</pre>";
        
        // If it's the animals table, also show sample data
        if ($table === 'animals') {
            echo "<h3>Sample data from animals table:</h3>";
            $data = $pdo->query("SELECT * FROM animals LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }
    }
    
} catch (PDOException $e) {
    echo "<h2>Error:</h2>";
    echo "<pre>";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
    echo "</pre>";
}
?> 