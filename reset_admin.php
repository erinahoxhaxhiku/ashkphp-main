<?php
require_once 'config/database.php';

try {
    // Create a new password hash for 'admin123'
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Delete existing admin user if exists
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    // Insert new admin user
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute(['admin', $hash, 'admin@animalshelter.com', 'admin']);
    
    if ($result) {
        echo "Admin user created successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        echo "Failed to create admin user.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 