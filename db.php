<?php
// Database Configuration - Supports Docker Environment Variables
// Suppress ALL errors that could break JSON output
error_reporting(0);
ini_set('display_errors', 0);

// Robust environment variable reader (works with Apache module, CGI, CLI)
function _orlia_env($name, $default = '') {
    $val = getenv($name);
    if ($val !== false && $val !== '') return $val;
    if (isset($_ENV[$name]) && $_ENV[$name] !== '') return $_ENV[$name];
    if (isset($_SERVER[$name]) && $_SERVER[$name] !== '') return $_SERVER[$name];
    return $default;
}

$servername = _orlia_env('DB_HOST', 'localhost');
$username   = _orlia_env('DB_USER', 'root');
$password   = _orlia_env('DB_PASSWORD', '');
$dbname     = _orlia_env('DB_NAME', 'orlia');

// Retry logic for Docker environments (MySQL may need a moment after healthcheck)
$conn = false;
$maxRetries = 5;
for ($i = 0; $i < $maxRetries; $i++) {
    $conn = @mysqli_connect($servername, $username, $password, $dbname);
    if ($conn) {
        mysqli_set_charset($conn, "utf8mb4");
        break;
    }
    if ($i < $maxRetries - 1) {
        usleep(500000); // 500ms between retries
    }
}

// Log connection error for debugging (to Apache error log, not screen)
if (!$conn) {
    error_log("Orlia DB Connection Failed: [" . mysqli_connect_errno() . "] " . mysqli_connect_error() . " (Host: $servername, User: $username, DB: $dbname)");
}
?>