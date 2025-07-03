<?php
require_once 'config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch();
    
    echo "<h2>Admin User Check:</h2>";
    if ($admin) {
        echo "<p>Admin user exists:</p>";
        echo "<pre>";
        print_r([
            'id' => $admin['id'],
            'username' => $admin['username'],
            'email' => $admin['email'],
            'role' => $admin['role'],
            'password_length' => strlen($admin['password'])
        ]);
        echo "</pre>";
        
        // Test password verification
        $testPassword = 'admin123';
        $isValid = password_verify($testPassword, $admin['password']);
        echo "<p>Password 'admin123' verification result: " . ($isValid ? 'VALID' : 'INVALID') . "</p>";
        
        if (!$isValid) {
            // Update admin password
            echo "<p>Updating admin password...</p>";
            $newHash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$newHash, 'admin']);
            echo "<p>Admin password updated.</p>";
        }
    } else {
        echo "<p>Admin user does not exist. Creating...</p>";
        
        // Create admin user
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', $hash, 'admin@animalshelter.com', 'admin']);
        
        echo "<p>Admin user created with ID: " . $pdo->lastInsertId() . "</p>";
    }
    
    // Show all users
    echo "<h2>All Users:</h2>";
    $users = $pdo->query("SELECT id, username, email, role FROM users")->fetchAll();
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "<pre>";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "</pre>";
}
?> 