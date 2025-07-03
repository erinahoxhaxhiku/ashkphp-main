<?php
// First line must be empty to prevent BOM issues

// Disable error display
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Start output buffering
ob_start();

// Set JSON content type
header('Content-Type: application/json');

// Clear any existing output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Send a test JSON response
echo json_encode([
    'success' => true,
    'message' => 'Test JSON response',
    'timestamp' => time()
]);
exit; 