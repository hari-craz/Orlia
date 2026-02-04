<?php
// Health Check Endpoint for Portainer/Docker
header('Content-Type: application/json');

$status = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => []
];

// Check PHP
$status['checks']['php'] = [
    'status' => 'ok',
    'version' => phpversion()
];

// Check Database Connection
try {
    $servername = getenv('DB_HOST') ?: "localhost";
    $username = getenv('DB_USER') ?: "root";
    $password = getenv('DB_PASSWORD') ?: "";
    $dbname = getenv('DB_NAME') ?: "orlia";
    
    $conn = @mysqli_connect($servername, $username, $password, $dbname);
    
    if ($conn) {
        $status['checks']['database'] = [
            'status' => 'ok',
            'host' => $servername,
            'database' => $dbname
        ];
        
        // Check tables exist
        $result = mysqli_query($conn, "SHOW TABLES");
        $tables = [];
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
        }
        $status['checks']['tables'] = [
            'status' => 'ok',
            'count' => count($tables),
            'tables' => $tables
        ];
        
        mysqli_close($conn);
    } else {
        $status['status'] = 'unhealthy';
        $status['checks']['database'] = [
            'status' => 'error',
            'error' => mysqli_connect_error(),
            'host' => $servername
        ];
    }
} catch (Exception $e) {
    $status['status'] = 'unhealthy';
    $status['checks']['database'] = [
        'status' => 'error',
        'error' => $e->getMessage()
    ];
}

// Check uploads directory
$uploadsDir = __DIR__ . '/uploads';
$status['checks']['uploads'] = [
    'status' => is_writable($uploadsDir) ? 'ok' : 'error',
    'path' => $uploadsDir,
    'writable' => is_writable($uploadsDir)
];

http_response_code($status['status'] === 'healthy' ? 200 : 503);
echo json_encode($status, JSON_PRETTY_PRINT);
?>
