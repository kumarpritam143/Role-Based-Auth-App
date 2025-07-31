<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

echo json_encode([
    'success' => true,
    'user' => [
        'username' => $_SESSION['user']['username'],
        'role' => $_SESSION['user']['role']
    ]
]);
?>
