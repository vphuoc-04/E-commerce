<?php
$pdo = new PDO("mysql:host=localhost;dbname=webbanhang;charset=utf8", "root", "");

$migrationFiles = glob(__DIR__ . "/tables/*.php");

foreach ($migrationFiles as $file) {
    echo "Running migration: " . basename($file) . PHP_EOL;
    $queries = include $file;

    foreach ($queries as $sql) {
        $pdo->exec($sql);
    }
}
