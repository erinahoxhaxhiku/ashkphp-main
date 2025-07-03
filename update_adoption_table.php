<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

try {
    // Drop and recreate the adoption_applications table
    $sql = "DROP TABLE IF EXISTS adoption_applications;
            CREATE TABLE adoption_applications (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                animal_id INT NOT NULL,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (animal_id) REFERENCES animals(id)
            );";
    
    $pdo->exec($sql);
    echo "Successfully updated adoption_applications table structure!";
} catch (PDOException $e) {
    die("Error updating table structure: " . $e->getMessage());
} 