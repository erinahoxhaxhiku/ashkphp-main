<?php
require_once 'config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Check if users table exists
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h2>Tables in database:</h2>";
    echo "<pre>";
    print_r($tables);
    echo "</pre>";
    
    if (!in_array('users', $tables)) {
        echo "<p>Creating users table...</p>";
        
        // Create users table
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            role ENUM('user', 'admin') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
        
        // Create admin user
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['admin', $adminPassword, 'admin@example.com', 'admin']);
        
        echo "<p>Users table created and admin user added.</p>";
    }
    
    // Show table structure
    echo "<h2>Users table structure:</h2>";
    $structure = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($structure);
    echo "</pre>";
    
    // Show all users (without passwords)
    echo "<h2>Current users:</h2>";
    $users = $pdo->query("SELECT id, username, email, role, created_at FROM users")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "<h2>Error:</h2>";
    echo "<pre>";
    echo "Message: " . $e->getMessage() . "\n";
    echo "</pre>";
}
?> 