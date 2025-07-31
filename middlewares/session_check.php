<?php
session_start();

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo "Unauthorized. Please log in.";
    exit;
}
