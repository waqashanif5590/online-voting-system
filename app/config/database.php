<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'livevoting_db';

$pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);
if (!$pdo) {
    echo 'Connection failed';
}
require_once APP_ROOT . '/app/helpers/update_election_status.php';
