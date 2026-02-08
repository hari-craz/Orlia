<?php
$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbname = getenv('DB_NAME') ?: "orlia";

// Try app user first, fallback to root
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    // Fallback: try root credentials
    $rootPass = getenv('DB_ROOT_PASSWORD') ?: "orlia_root";
    $conn = @mysqli_connect($servername, "root", $rootPass, $dbname);
}

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>