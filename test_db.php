<?php
// Debug script to test database connection
header('Content-Type: application/json');

$servername = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? ($_SERVER['DB_HOST'] ?? "db"));
$username = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? ($_SERVER['DB_USER'] ?? "root"));
$password = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? ($_SERVER['DB_PASSWORD'] ?? "rootpassword"));
$dbname = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? ($_SERVER['DB_NAME'] ?? "orlia"));

$response = [
    'config' => [
        'host' => $servername,
        'user' => $username,
        'database' => $dbname,
        'password_set' => !empty($password)
    ]
];

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        $response['status'] = 'error';
        $response['message'] = 'Connection failed: ' . $conn->connect_error;
    } else {
        $response['status'] = 'success';
        $response['message'] = 'Connected successfully';
        
        // Check if tables exist
        $tables = [];
        $result = $conn->query("SHOW TABLES");
        if ($result) {
            while ($row = $result->fetch_array()) {
                $tables[] = $row[0];
            }
        }
        $response['tables'] = $tables;
        $conn->close();
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Exception: ' . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
