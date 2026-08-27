<?php

namespace App\Config;

use PDO;

class OnlineDB
{
    private string $host = "codigo42-grandej2004-0d96.k.aivencloud.com";
    private int $port = 16952;
    private string $database = "codigo42";
    private string $username = "avnadmin";
    private string $password = "AVNS_QbYx94IC09VRINccaO9";

    public function conectar(): PDO
    {
        $caPath = __DIR__ . '/../../certificates/ca.pem';

        $dsn = "mysql:";
        $dsn .= "host={$this->host};";
        $dsn .= "port={$this->port};";
        $dsn .= "dbname={$this->database};";
        $dsn .= "sslmode=verify-ca;";
        $dsn .= "sslrootcert={$caPath}";

        return new PDO(
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