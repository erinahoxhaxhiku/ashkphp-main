<?php
class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Register new user
    public function register($username, $password, $email) {
        try {
            // Check if username already exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                return false;
            }
            
            // Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')");
            return $stmt->execute([$username, $hash, $email]);
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Login user
    public function login($username, $password) {
        try {
            error_log("Login attempt for username: " . $username);
            
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            error_log("Database query executed. User found: " . ($user ? "Yes" : "No"));
            
            if ($user) {
                error_log("User details: " . print_r([
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'password_length' => strlen($user['password'])
                ], true));
                
                if (password_verify($password, $user['password'])) {
                    error_log("Password verification successful");
                    
                    // Start session if not already started
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    error_log("Session data set: " . print_r($_SESSION, true));
                    return true;
                } else {
                    error_log("Password verification failed");
                    error_log("Input password length: " . strlen($password));
                    error_log("Stored hash length: " . strlen($user['password']));
                    return false;
                }
            }
            
            error_log("Login failed - user not found");
            return false;
            
        } catch (Exception $e) {
            error_log("Login error in User class: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
    
    // Check if user is admin
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    
    // Logout user
    public function logout() {
        error_log("Logging out user: " . ($_SESSION['username'] ?? 'unknown'));
        error_log("Session data before logout: " . print_r($_SESSION, true));
        
        // Clear all session data
        $_SESSION = array();
        
        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        // Destroy the session
        session_destroy();
        
        error_log("Logout complete - session destroyed");
        return true;
    }
    
    // Get user by ID
    public function getById($id) {
        $sql = "SELECT id, username, email, role, created_at FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
} 