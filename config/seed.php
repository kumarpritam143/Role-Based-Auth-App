<?php
include 'database.php';

$username = "admin";
$password = "Admin@123";
$role = "admin";

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $insert = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("sss", $username, $hashedPassword, $role);
    if ($stmt->execute()) {
        echo "Default admin created successfully.";
    } else {
        echo "Error creating admin: " . $stmt->error;
    }
} else {
    echo "Admin already exists.";
}

$conn->close();
?>
