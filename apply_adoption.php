<?php
session_start();
require_once 'config/database.php';
require_once 'classes/User.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to adopt.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$animal_id = isset($_POST['animal_id']) ? intval($_POST['animal_id']) : 0;

if ($animal_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid animal selected.']);
    exit;
}

// Check if user has already applied for this animal and status is pending or approved
$stmt = $pdo->prepare("SELECT * FROM adoption_applications WHERE user_id = ? AND animal_id = ? AND status IN ('pending', 'approved')");
$stmt->execute([$user_id, $animal_id]);
$existingApplication = $stmt->fetch();

if ($existingApplication) {
    echo json_encode(['success' => false, 'message' => 'You have already applied to adopt this animal.']);
    exit;
}

// Insert new adoption application
$insert = $pdo->prepare("INSERT INTO adoption_applications (user_id, animal_id) VALUES (?, ?)");
$success = $insert->execute([$user_id, $animal_id]);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Your adoption application has been submitted.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit application. Please try again.']);
}
