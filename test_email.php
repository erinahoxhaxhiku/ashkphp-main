<?php
// Enable error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/smtp_error.log');

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

try {
    echo "Creating PHPMailer instance...<br>";
    $mail = new PHPMailer(true);

    echo "Setting up debug output...<br>";
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) {
        echo "Debug ($level): $str<br>";
        error_log("Debug ($level): $str");
    };

    echo "Configuring SMTP...<br>";
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'erinahoxhaxhiku24@gmail.com';
    $mail->Password = 'ozqx uflg waff anha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    echo "Setting up email content...<br>";
    $mail->setFrom('erinahoxhaxhiku24@gmail.com', 'Test Email');
    $mail->addAddress('erinahoxhaxhiku24@gmail.com');
    $mail->Subject = 'Test Email ' . date('Y-m-d H:i:s');
    $mail->Body = 'This is a test email to verify SMTP settings.';

    echo "Attempting to send email...<br>";
    if (!$mail->send()) {
        throw new Exception('Email could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }

    echo "Email sent successfully!<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    error_log("Error in test_email.php: " . $e->getMessage());
} 