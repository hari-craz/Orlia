<?php
// Database Setup Script - Run once to initialize tables
// Access: https://your-domain/setup.php
error_reporting(0);
header('Content-Type: application/json');

$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbname = getenv('DB_NAME') ?: "orlia";

$response = ['step' => 'init'];

try {
    // Connect without database first
    $conn = @mysqli_connect($servername, $username, $password);
    
    if (!$conn) {
        echo json_encode([
            'status' => 'error',
            'step' => 'connect',
            'message' => 'Cannot connect to MySQL: ' . mysqli_connect_error(),
            'host' => $servername
        ]);
        exit;
    }
    
    $response['step'] = 'connected';
    
    // Create database if not exists
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$dbname`");
    mysqli_select_db($conn, $dbname);
    
    // Check if tables exist
    $result = mysqli_query($conn, "SHOW TABLES");
    $tableCount = $result ? mysqli_num_rows($result) : 0;
    
    if ($tableCount > 0) {
        $tables = [];
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
        }
        echo json_encode([
            'status' => 'already_initialized',
            'message' => "Database already has $tableCount tables",
            'tables' => $tables
        ]);
        exit;
    }
    
    // Try both path variations for Linux case-sensitivity
    $sqlPaths = [
        __DIR__ . '/assets/Schema/orlia.sql',
        __DIR__ . '/assets/schema/orlia.sql'
    ];
    
    $sqlFile = null;
    foreach ($sqlPaths as $path) {
        if (file_exists($path)) {
            $sqlFile = $path;
            break;
        }
    }
    
    if (!$sqlFile) {
        // List what's in assets folder for debugging
        $assetsDir = __DIR__ . '/assets';
        $assetContents = is_dir($assetsDir) ? scandir($assetsDir) : ['dir not found'];
        
        echo json_encode([
            'status' => 'error',
            'step' => 'find_sql',
            'message' => 'SQL file not found',
            'tried' => $sqlPaths,
            'assets_contents' => $assetContents
        ]);
        exit;
    }
    
    $sql = file_get_contents($sqlFile);
    
    if (empty($sql)) {
        echo json_encode([
            'status' => 'error',
            'step' => 'read_sql',
            'message' => 'SQL file is empty',
            'file' => $sqlFile
        ]);
        exit;
    }
    
    // Execute multi-query
    if (!mysqli_multi_query($conn, $sql)) {
        echo json_encode([
            'status' => 'error',
            'step' => 'exec_sql',
            'message' => 'SQL execution failed: ' . mysqli_error($conn)
        ]);
        exit;
    }
    
    // Process all results to clear them
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    
    // Verify tables were created
    $result = mysqli_query($conn, "SHOW TABLES");
    $newTableCount = $result ? mysqli_num_rows($result) : 0;
    $tables = [];
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
        }
    }
    
    mysqli_close($conn);
    
    echo json_encode([
        'status' => 'success',
        'message' => "Database initialized with $newTableCount tables",
        'tables' => $tables,
        'sql_file' => $sqlFile
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'step' => 'exception',
        'message' => $e->getMessage()
    ]);
}
?>
