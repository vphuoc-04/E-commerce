<?php
class Database {
    private static $pdo;

    public static function connect() {
        if (!self::$pdo) {
            $dsn = "mysql:host=localhost;dbname=webbanhang;charset=utf8";
            $username = "root";
            $password = "";

            try {
                self::$pdo = new PDO($dsn, $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}