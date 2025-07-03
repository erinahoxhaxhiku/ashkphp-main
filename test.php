<?php
// Disable error reporting
error_reporting(0);
ini_set('display_errors', '0');

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Simple response
echo json_encode([
    'test' => true,
    'time' => time(),
    'message' => 'Test successful'
]); 