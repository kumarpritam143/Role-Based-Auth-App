<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); 
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $username = trim($input['username'] ?? '');
    $password = trim($input['password'] ?? '');
    $role = trim($input['role'] ?? 'student');

    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Please fill all fields.']);
        exit;
    }

    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Username already taken.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $role);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Registration successful. Please log in.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Try again.']);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
