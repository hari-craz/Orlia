<?php
// Simple test endpoint to debug backend issues
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$result = [
    'step' => 'init',
    'php_version' => PHP_VERSION,
    'errors' => []
];

// Test 1: Environment variables
$result['env'] = [
    'DB_HOST' => getenv('DB_HOST') ?: 'not set (using localhost)',
    'DB_USER' => getenv('DB_USER') ?: 'not set (using root)',
    'DB_NAME' => getenv('DB_NAME') ?: 'not set (using orlia)'
];

// Test 2: Database connection
$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbname = getenv('DB_NAME') ?: "orlia";

$conn = @mysqli_connect($servername, $username, $password, $dbname);
if ($conn) {
    $result['database'] = 'connected';
    
    // Test 3: Query users table
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    if ($query) {
        $row = mysqli_fetch_assoc($query);
        $result['users_count'] = $row['count'];
    } else {
        $result['users_query'] = 'failed: ' . mysqli_error($conn);
    }
    
    // Test 4: Check if admin user exists
    $adminQuery = mysqli_query($conn, "SELECT userid, role FROM users WHERE userid='admin' LIMIT 1");
    if ($adminQuery && mysqli_num_rows($adminQuery) > 0) {
        $result['admin_exists'] = true;
    } else {
        $result['admin_exists'] = false;
    }
    
    mysqli_close($conn);
} else {
    $result['database'] = 'failed: ' . mysqli_connect_error();
}

// Test 5: Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$result['session'] = session_id() ? 'active' : 'failed';

// Clear any buffered output
$buffered = ob_get_clean();
if (!empty($buffered)) {
    $result['buffered_output'] = $buffered;
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
