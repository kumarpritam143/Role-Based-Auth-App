<?php
include '../config/database.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];

        while ($row = $result->fetch_assoc()) {
            unset($row['password']);
            $users[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $users]);
        $stmt->close();
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');
        $role = trim($data['role'] ?? 'student');

        if (empty($username) || empty($password) || empty($role)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
            exit;
        }

        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            $check->close();
            exit;
        }
        $check->close();

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed, $role);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User created successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
        break;

    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $params);
        $id = intval($params['id'] ?? 0);
        $input = json_decode(file_get_contents("php://input"), true);

        $username = $input["username"] ?? '';
        $role = $input["role"] ?? '';

        if ($id <= 0 || empty($username) || empty($role)) {
            echo json_encode(["success" => false, "message" => "Missing or invalid data"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $role, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update user"]);
        }

        $stmt->close();
        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $params);
        $id = intval($params['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete user"]);
        }

        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        break;
}

$conn->close();
