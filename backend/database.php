<?php

declare(strict_types=1);

use PDO;

class Database
{

    public function getConnection(): PDO
    {
        $dsn = "mysql:host=127.0.0.1;dbname=postmanager_db;charset=utf8";
        $username = "root";
        $password = "";

        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            echo "Db baglanti hatasi: " . $e->getMessage();
            throw $e;
        }

        return $pdo;
    }

}