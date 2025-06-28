<?php
class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Register new user
    public function register($username, $password, $email) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':email' => $email
            ]);
        } catch (PDOException $e) {
            error_log("Database error during registration: " . $e->getMessage());
            return false;
        }
    }
    
    // Login user
    public function login($username, $password) {
        try {
            // Debug: Log login attempt
            error_log("Starting login process for username: " . $username);
            
            // Check if username exists
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();
            
            // Debug: Log user lookup result
            if ($user) {
                error_log("User found in database: " . $username);
                error_log("User role: " . $user['role']);
                error_log("Stored password hash length: " . strlen($user['password']));
            } else {
                error_log("User not found in database: " . $username);
                return false;
            }
            
            // Verify password
            $passwordValid = password_verify($password, $user['password']);
            error_log("Password verification result: " . ($passwordValid ? "SUCCESS" : "FAILED"));
            
            if ($passwordValid) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Debug: Log successful login
                error_log("Login successful for user: " . $username);
                error_log("Session data after login: " . print_r($_SESSION, true));
                
                return true;
            } else {
                error_log("Password verification failed for user: " . $username);
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database error during login: " . $e->getMessage());
            error_log("SQL State: " . $e->errorInfo[0]);
            error_log("Error Code: " . $e->errorInfo[1]);
            error_log("Error Message: " . $e->errorInfo[2]);
            return false;
        }
    }
    
    // Check if user is admin
    public function isAdmin() {
        if (!isset($_SESSION['user_id'])) {
            error_log("No user_id in session");
            return false;
        }
        
        try {
            $sql = "SELECT role FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $user = $stmt->fetch();
            
            $isAdmin = $user && $user['role'] === 'admin';
            error_log("isAdmin check for user ID " . $_SESSION['user_id'] . ": " . ($isAdmin ? 'true' : 'false'));
            
            return $isAdmin;
        } catch (PDOException $e) {
            error_log("Database error checking admin status: " . $e->getMessage());
            return false;
        }
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