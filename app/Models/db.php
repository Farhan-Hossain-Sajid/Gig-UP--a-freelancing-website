<?php
namespace App\Models;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=freelance_db;charset=utf8mb4',
                    'root',
                    '',
                    [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
                );
            } catch (PDOException $e) {
                exit("DB connection failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
