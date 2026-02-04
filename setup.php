<?php
// Database Setup Script - Run once to initialize tables
// Access: https://your-domain/setup.php

header('Content-Type: application/json');

$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbname = getenv('DB_NAME') ?: "orlia";

// Connect without database first
$conn = @mysqli_connect($servername, $username, $password);

if (!$conn) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Cannot connect to MySQL: ' . mysqli_connect_error()
    ]);
    exit;
}

// Create database if not exists
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$dbname`");
mysqli_select_db($conn, $dbname);

// Check if tables exist
$result = mysqli_query($conn, "SHOW TABLES");
$tableCount = mysqli_num_rows($result);

if ($tableCount > 0) {
    echo json_encode([
        'status' => 'already_initialized',
        'message' => "Database already has $tableCount tables. No action needed.",
        'tables' => $tableCount
    ]);
    exit;
}

// Read and execute SQL file
$sqlFile = __DIR__ . '/assets/Schema/orlia.sql';

if (!file_exists($sqlFile)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'SQL file not found: ' . $sqlFile
    ]);
    exit;
}

$sql = file_get_contents($sqlFile);

// Execute multi-query
mysqli_multi_query($conn, $sql);

// Process all results to clear them
do {
    if ($result = mysqli_store_result($conn)) {
        mysqli_free_result($result);
    }
} while (mysqli_next_result($conn));

// Check for errors
if (mysqli_error($conn)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'SQL Error: ' . mysqli_error($conn)
    ]);
    exit;
}

// Verify tables were created
$result = mysqli_query($conn, "SHOW TABLES");
$newTableCount = mysqli_num_rows($result);
$tables = [];
while ($row = mysqli_fetch_array($result)) {
    $tables[] = $row[0];
}

mysqli_close($conn);

echo json_encode([
    'status' => 'success',
    'message' => "Database initialized successfully with $newTableCount tables",
    'tables' => $tables
]);
?>
