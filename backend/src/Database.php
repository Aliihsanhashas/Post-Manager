<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class Database
{
    public function getConnection(): PDO
    {
        $dsn = "mysql:host=127.0.0.1;dbname=postmanager_db;charset=utf8";
        $username = "root";
        $password = "";

        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            echo "Veritabani bağlanti hatasi: " . $e->getMessage() . "\n";
            throw $e;
        }

        return $pdo;
    }
}