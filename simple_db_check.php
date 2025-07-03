<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS animalshelter");
    
    // Connect to our database
    $pdo = new PDO("mysql:host=localhost;dbname=animalshelter", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS animals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            species VARCHAR(50) NOT NULL,
            description TEXT,
            status ENUM('available', 'pending', 'adopted') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            animal_id INT,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
        )
    ");
    
    // Add sample data if no animals exist
    $count = $pdo->query("SELECT COUNT(*) FROM animals")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("
            INSERT INTO animals (name, species, description, status) VALUES
            ('Max', 'Dog', 'A friendly golden retriever who loves to play fetch.', 'available'),
            ('Luna', 'Cat', 'A gentle calico cat who enjoys lounging in the sun.', 'available'),
            ('Rocky', 'Dog', 'An energetic German Shepherd mix, great with kids.', 'pending'),
            ('Bella', 'Cat', 'A playful Siamese cat looking for a forever home.', 'available'),
            ('Charlie', 'Dog', 'A sweet Beagle puppy who loves attention.', 'available')
        ");
        
        echo "Added sample animals.<br>";
    }
    
    // Show all animals
    echo "<h2>Animals in database:</h2>";
    $animals = $pdo->query("SELECT * FROM animals")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($animals);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 