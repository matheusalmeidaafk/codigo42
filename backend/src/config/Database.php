<?php
namespace App\Config;

use PDO;

class Database {
    private string $host = "localhost";
    private string $database = "codigo42";
    private string $username = "root";
    private string $password = "";

    public function conectar(): PDO {
        $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";

        return new PDO (
            $dsn,
            $this->username,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
}