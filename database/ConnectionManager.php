<?php

namespace Database;

use PDO;
use PDOException;

class ConnectionManager
{
    protected static ?PDO $pdo = null;

    public static function getPdo(?string $connection = null): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $config = require __DIR__ . '/../config/database.php';
        $connection = $connection ?? $config['default'];
        $conf = $config['eloquent'][$connection] ?? null;

        if (!$conf) {
            throw new \Exception("Database connection config not found: {$connection}");
        }

        $dsn = match ($conf['driver']) {
            'mysql'  => "mysql:host={$conf['host']};port={$conf['port']};dbname={$conf['database']};charset={$conf['charset']}",
            'pgsql'  => "pgsql:host={$conf['host']};port={$conf['port']};dbname={$conf['database']}",
            'sqlite' => "sqlite:{$conf['database']}",
            'sqlsrv' => "sqlsrv:Server={$conf['host']},{$conf['port']};Database={$conf['database']}",
            default  => throw new \Exception("Unsupported driver: {$conf['driver']}"),
        };

        try {
            self::$pdo = new PDO($dsn, $conf['username'] ?? null, $conf['password'] ?? null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \Exception("PDO connection failed: " . $e->getMessage());
        }

        return self::$pdo;
    }
}
