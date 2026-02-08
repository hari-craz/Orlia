<?php
// Simple health check endpoint for Portainer/Docker diagnostics
// Access: http://192.168.13.6:10012/healthcheck.php
header('Content-Type: application/json');

$checks = [];

// 1. Check environment variables
$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD') ? '***SET***' : 'NOT SET';
$dbName = getenv('DB_NAME');

$checks['env_vars'] = [
    'DB_HOST' => $dbHost ?: 'NOT SET (fallback: localhost)',
    'DB_USER' => $dbUser ?: 'NOT SET (fallback: root)',
    'DB_PASSWORD' => $dbPass,
    'DB_NAME' => $dbName ?: 'NOT SET (fallback: orlia)',
];

// Also check $_ENV and $_SERVER
$checks['env_via_ENV'] = [
    'DB_HOST' => isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'NOT IN $_ENV',
    'DB_USER' => isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : 'NOT IN $_ENV',
];
$checks['env_via_SERVER'] = [
    'DB_HOST' => isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : 'NOT IN $_SERVER',
    'DB_USER' => isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : 'NOT IN $_SERVER',
];

// 2. Check DNS resolution of DB host
$resolvedHost = getenv('DB_HOST') ?: 'localhost';
$checks['dns_resolve'] = @gethostbyname($resolvedHost);
$checks['dns_host'] = $resolvedHost;

// 3. Test TCP connection to MySQL port
$socket = @fsockopen($resolvedHost, 3306, $errno, $errstr, 5);
if ($socket) {
    $checks['tcp_3306'] = 'OPEN';
    fclose($socket);
} else {
    $checks['tcp_3306'] = "CLOSED ($errno: $errstr)";
}

// 4. Test MySQL connection
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: 'orlia';

$conn = @mysqli_connect($host, $user, $pass, $db);
if ($conn) {
    $checks['mysql_connection'] = 'SUCCESS';
    $checks['mysql_server_info'] = mysqli_get_server_info($conn);
    
    // Test query
    $result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $checks['users_count'] = $row['cnt'];
    } else {
        $checks['users_count'] = 'QUERY FAILED: ' . mysqli_error($conn);
    }
    
    mysqli_close($conn);
} else {
    $checks['mysql_connection'] = 'FAILED';
    $checks['mysql_error'] = mysqli_connect_error();
    $checks['mysql_errno'] = mysqli_connect_errno();
}

// 5. PHP info
$checks['php_version'] = phpversion();
$checks['mysqli_available'] = extension_loaded('mysqli') ? 'YES' : 'NO';

echo json_encode($checks, JSON_PRETTY_PRINT);
