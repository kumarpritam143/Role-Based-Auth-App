<?php
session_start();
header('Content-Type: application/json');

// Check session
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get raw JSON input
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

// Validate JSON input
if (is_null($data)) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

// Get and sanitize new password
$newPassword = isset($data['newPassword']) ? trim($data['newPassword']) : '';

if (empty($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Password cannot be empty']);
    exit;
}

// Include DB connection
include '../config/database.php';

// Get username from session
$username = $_SESSION['user']['username'];

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update password in DB
$sql = "UPDATE users SET password = ? WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashedPassword, $username);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password']);
}

$stmt->close();
$conn->close();
