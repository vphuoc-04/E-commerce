<?php
$DB_HOST = 'localhost';
$DB_NAME = 'your_database';
$DB_USER = 'your_user';
$DB_PASS = 'your_password';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // Trong production nên log thay vì echo
    die("Database connection failed: " . $e->getMessage());
}