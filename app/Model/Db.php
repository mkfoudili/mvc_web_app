<?php
require_once "Config.php";

class DB {
    private static $pdo;
    public static function conn(){
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
                    DB_USER,
                    DB_PASS
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}