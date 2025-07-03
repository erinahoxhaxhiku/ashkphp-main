<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Register shutdown function to handle fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'A server error occurred'
        ]);
        exit;
    }
});

// Prevent any output buffering issues
while (ob_get_level()) {
    ob_end_clean();
}

// Start fresh output buffer
ob_start();

// Disable error display
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Set JSON header immediately
header('Content-Type: application/json');

// Function to send JSON response
function sendJsonResponse($success, $message, $debug = null) {
    $response = ['success' => $success, 'message' => $message];
    if ($debug && isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
        $response['debug'] = $debug;
    }
    
    // Clear any previous output
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    echo json_encode($response);
    exit;
}

// Catch any uncaught exceptions
set_exception_handler(function($e) {
    sendJsonResponse(false, 'An error occurred', $e->getMessage());
});

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse(false, 'Invalid request method');
    }

    // Load PHPMailer
    if (!file_exists(__DIR__ . '/PHPMailer/src/PHPMailer.php')) {
        sendJsonResponse(false, 'Server configuration error');
    }

    require_once __DIR__ . '/PHPMailer/src/Exception.php';
    require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/src/SMTP.php';

    // Get and validate inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($name) || empty($email) || empty($message)) {
        sendJsonResponse(false, 'All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse(false, 'Invalid email format');
    }

    // Create PHPMailer instance
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'erinahoxhaxhiku24@gmail.com';
    $mail->Password = 'ozqx uflg waff anha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Set up email
    $mail->setFrom('erinahoxhaxhiku24@gmail.com', 'Animal Shelter Contact Form');
    $mail->addAddress('erinahoxhaxhiku24@gmail.com');
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);

    // Email content
    $mail->Subject = 'New Contact Form Message from ' . $name;
    $mail->Body = "
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
    ";
    $mail->AltBody = "New Contact Form Submission\n\nName: $name\nEmail: $email\n\nMessage:\n$message";

    // Send email
    if (!$mail->send()) {
        sendJsonResponse(false, 'Failed to send email', $mail->ErrorInfo);
    }

    sendJsonResponse(true, 'Thank you for your message! We will get back to you soon.');

} catch (Throwable $e) {
    sendJsonResponse(false, 'An error occurred', $e->getMessage());
} 