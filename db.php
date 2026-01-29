<?php
// Get environment variables with multiple fallback methods
$servername = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? ($_SERVER['DB_HOST'] ?? "db"));
$username = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? ($_SERVER['DB_USER'] ?? "root"));
$password = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? ($_SERVER['DB_PASSWORD'] ?? "rootpassword"));
$dbname = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? ($_SERVER['DB_NAME'] ?? "orlia"));

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if($conn->connect_error){
    error_log("Database connection failed: " . $conn->connect_error);
    header('Content-Type: application/json');
    echo json_encode(['status' => 500, 'message' => 'Database connection failed']);
    exit;
}

// Set charset to utf8
$conn->set_charset("utf8");
?>