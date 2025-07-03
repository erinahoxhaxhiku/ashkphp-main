<?php
require_once 'config/database.php';

try {
    // Create a new password hash
    $password = 'admin123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Delete existing admin user if exists
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    // Create new admin user
    $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':username' => 'admin',
        ':email' => 'admin@animalshelter.com',
        ':password' => $hashedPassword,
        ':role' => 'admin'
    ]);
    
    if ($result) {
        echo "Admin user reset successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        echo "Failed to reset admin user.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 