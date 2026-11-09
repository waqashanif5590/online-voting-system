<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'livevoting_db';

try {
    $pdo = new PDO(
        "mysql:host=$server;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $error) {
    // Keep the real error in the server log, show only a simple message to the user.
    error_log('Database connection failed: ' . $error->getMessage());
    http_response_code(500);
    exit('The system is temporarily unavailable. Please try again later.');
}
require_once APP_ROOT . '/app/helpers/election_results.php';
require_once APP_ROOT . '/app/helpers/update_election_status.php';
