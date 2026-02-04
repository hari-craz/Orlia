<?php
// Database Configuration - Supports Docker Environment Variables
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

// Check connection
if (!$conn) {
    // Log error for debugging
    error_log("Database connection failed after $maxRetries attempts: " . mysqli_connect_error());
    
    // Return JSON error for API calls
    if (strpos($_SERVER['REQUEST_URI'] ?? '', 'backend.php') !== false) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 500,
            'message' => 'Database connection failed. Please try again later.'
        ]);
        exit;
    }
    
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset for proper UTF-8 support
mysqli_set_charset($conn, "utf8mb4");
?>