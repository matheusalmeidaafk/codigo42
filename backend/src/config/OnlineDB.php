<?php

namespace App\Config;

use PDO;

class OnlineDB
{
    public function conectar(): PDO
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $database = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        $caPath = __DIR__ . '/../../certificates/ca.pem';

        $dsn = "mysql:";
        $dsn .= "host={$host};";
        $dsn .= "port={$port};";
        $dsn .= "dbname={$database};";
        $dsn .= "charset=utf8mb4";

        return new PDO(
            $dsn,
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_SSL_CA => $caPath
            ]
        );
    }
}