<?php declare(strict_types=1);
// src/Models/Database.php

namespace App\Models;

use PDO;
use PDOException;

final class Database {
    private static ?PDO $pdo = null;

    public static function getConnection(array $cfg): PDO {
        if (self::$pdo instanceof PDO) return self::$pdo;

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['host'],
            $cfg['port'],
            $cfg['name'],
            $cfg['charset'] ?? 'utf8mb4'
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => false,
        ];

        try {
            self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], $options);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('DB connection error.');
        }
        return self::$pdo;
    }
}


