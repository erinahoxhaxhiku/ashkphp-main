<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

try {
    // Check if table exists
    $sql = "SHOW TABLES LIKE 'adoption_applications'";
    $result = $pdo->query($sql);
    
    if ($result->rowCount() == 0) {
        // Table doesn't exist, create it
        $sql = "CREATE TABLE adoption_applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            animal_id INT NOT NULL,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
        )";
        $pdo->exec($sql);
        echo "Table created successfully!\n";
    } else {
        // Table exists, check its structure
        $sql = "SHOW COLUMNS FROM adoption_applications";
        $result = $pdo->query($sql);
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);
        
        $hasApplicationDate = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'application_date') {
                $hasApplicationDate = true;
                break;
            }
        }
        
        if (!$hasApplicationDate) {
            // Add the missing column
            $sql = "ALTER TABLE adoption_applications ADD COLUMN application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
            $pdo->exec($sql);
            echo "Added missing application_date column!\n";
        } else {
            echo "Table structure is correct!\n";
        }
    }
    
    // Show current structure
    $sql = "DESCRIBE adoption_applications";
    $result = $pdo->query($sql);
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nCurrent table structure:\n";
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . "\n";
    }
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} 