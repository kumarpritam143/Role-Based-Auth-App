<?php
$host = "localhost";
$user = "root";         
$password = "";           
$database = "Role-Based Authentication Web Application";    

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
