<?php
// Database Configuration - Supports Docker Environment Variables
// Suppress ALL errors that could break JSON output
error_reporting(0);
ini_set('display_errors', 0);

$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbname = getenv('DB_NAME') ?: "orlia";

// Retry connection for Docker container startup timing
$maxRetries = 5;
$retryDelay = 2; // seconds
$conn = null;

for ($i = 0; $i < $maxRetries; $i++) {
    $conn = @mysqli_connect($servername, $username, $password, $dbname);
    if ($conn) {
        break;
    }
    if ($i < $maxRetries - 1) {
        sleep($retryDelay);
    }
}

// Set charset for proper UTF-8 support if connected
if ($conn) {
    mysqli_set_charset($conn, "utf8mb4");
}
// Do NOT output anything here - let calling script handle errors
?>