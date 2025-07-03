<?php
require_once 'config/database.php';
require_once 'classes/User.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug: Check PHP version and loaded extensions
error_log("PHP Version: " . PHP_VERSION);
error_log("Loaded extensions: " . implode(", ", get_loaded_extensions()));

// Debug: Check session configuration
error_log("Session save path: " . session_save_path());
error_log("Session name: " . session_name());
error_log("Session ID: " . session_id());
error_log("Session status: " . session_status());

// Debug: Check if session is working
error_log("Session data: " . print_r($_SESSION, true));

// If user is already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    error_log("User already logged in, redirecting...");
    header('Location: index.php');
    exit();
}

$error = '';
$username = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug: Log all POST data
        error_log("POST data received: " . print_r($_POST, true));
        
        $user = new User($pdo);
        
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        // Debug information
        error_log("Login attempt - Username: " . $username);
        error_log("Password length: " . strlen($password));
        
        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password';
            error_log("Empty username or password");
        } else {
            // Debug: Check database connection
            try {
                $stmt = $pdo->query("SELECT 1");
                error_log("Database connection test successful");
                
                // Debug: Check if user exists
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $dbUser = $stmt->fetch();
                if ($dbUser) {
                    error_log("User found in database. Role: " . $dbUser['role']);
                    error_log("Stored password hash: " . $dbUser['password']);
                    
                    // Test password verification directly
                    $passwordValid = password_verify($password, $dbUser['password']);
                    error_log("Direct password verification result: " . ($passwordValid ? "VALID" : "INVALID"));
                } else {
                    error_log("User not found in database");
                }
            } catch (PDOException $e) {
                error_log("Database connection test failed: " . $e->getMessage());
            }
            
            if ($user->login($username, $password)) {
                error_log("Login successful! Session data: " . print_r($_SESSION, true));
                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid username or password. Please try again.';
                error_log("Login failed for username: " . $username);
            }
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        $error = 'An error occurred. Please try again later.';
    }
}

// Debug: Show all errors
error_log("PHP errors: " . print_r(error_get_last(), true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Animal Shelter Kosovo</title>
    <!-- Add base URL for consistent file paths -->
    <?php
    $base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $base_url .= "://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    ?>
    <base href="<?php echo $base_url; ?>/">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /* Ensure form inputs are visible */
        .auth-form input[type="text"],
        .auth-form input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #ffffff;
            color: #000000;
            margin-bottom: 1rem;
        }
        
        .auth-form {
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            width: 100%;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #16a34a);
            color: #ffffff;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
        }
        
        .auth-links {
            margin-top: 1rem;
            text-align: center;
        }
        
        .auth-links a {
            color: #2563eb;
            text-decoration: none;
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h1>Login</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo htmlspecialchars($username); ?>"
                           autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required
                           autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <p class="auth-links">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
            
            <p class="auth-links">
                <a href="index.php">Back to Home</a>
            </p>
        </div>
    </div>
    
    <!-- Debug information -->
    <?php if (true): // Always show debug info for now ?>
        <div style="margin: 2rem auto; max-width: 800px; padding: 1rem; background: #f3f4f6; border-radius: 8px;">
            <h3>Debug Information:</h3>
            <pre><?php
                echo "PHP Version: " . PHP_VERSION . "\n";
                echo "Session save path: " . session_save_path() . "\n";
                echo "Session ID: " . session_id() . "\n";
                echo "POST data: " . print_r($_POST, true) . "\n";
                echo "Session data: " . print_r($_SESSION, true) . "\n";
                
                // Test database connection
                try {
                    $testStmt = $pdo->query("SELECT COUNT(*) FROM users");
                    $userCount = $testStmt->fetchColumn();
                    echo "Number of users in database: " . $userCount . "\n";
                } catch (Exception $e) {
                    echo "Database error: " . $e->getMessage() . "\n";
                }
            ?></pre>
        </div>
    <?php endif; ?>
</body>
</html> 