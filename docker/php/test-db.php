<?php
// Load Laravel environment
require_once __DIR__ . '/../../vendor/autoload.php';

// Bootstrap Laravel application to access environment variables
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $host = env('DB_HOST', 'bssc_mysql');
    $database = env('DB_DATABASE', 'bssc');
    $username = env('DB_USERNAME', 'root');
    $password = env('DB_PASSWORD', 'password');
    
    $pdo = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
    echo "Database: OK\n";
} catch (Exception $e) {
    echo "Database: FAILED\n";
}
?> 