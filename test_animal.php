<?php
require_once 'config/database.php';
require_once 'classes/Animal.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Animal Management</h1>";

try {
    // First, check if tables exist
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h2>Existing Tables:</h2>";
    echo "<pre>";
    print_r($tables);
    echo "</pre>";

    // Check animals table structure
    if (in_array('animals', $tables)) {
        $structure = $pdo->query("DESCRIBE animals")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h2>Animals Table Structure:</h2>";
        echo "<pre>";
        print_r($structure);
        echo "</pre>";

        // Count existing animals
        $count = $pdo->query("SELECT COUNT(*) FROM animals")->fetchColumn();
        echo "<p>Current number of animals: $count</p>";

        // Add a test animal if none exist
        if ($count == 0) {
            $animal = new Animal($pdo);
            $data = [
                'name' => 'Max',
                'species' => 'Dog',
                'description' => 'A friendly golden retriever who loves to play.',
                'status' => 'available'
            ];
            
            $animalId = $animal->create($data);
            echo "<p>Created test animal with ID: $animalId</p>";
        }

        // Show all animals
        $animals = $pdo->query("SELECT * FROM animals")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h2>All Animals:</h2>";
        echo "<pre>";
        print_r($animals);
        echo "</pre>";
    } else {
        echo "<p>Animals table does not exist! Creating it now...</p>";
        
        // Create the animals table
        $sql = "CREATE TABLE IF NOT EXISTS animals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            species VARCHAR(50) NOT NULL,
            description TEXT,
            status ENUM('available', 'pending', 'adopted') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        
        // Create the images table if it doesn't exist
        if (!in_array('images', $tables)) {
            $sql = "CREATE TABLE IF NOT EXISTS images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                animal_id INT,
                file_name VARCHAR(255) NOT NULL,
                file_path VARCHAR(255) NOT NULL,
                uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
            )";
            $pdo->exec($sql);
        }
        
        echo "<p>Tables created successfully!</p>";
    }

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