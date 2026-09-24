<?php

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

class OnlineDB
{
    public function conectar(): PDO
    {
        $host = $_ENV['DB_HOST'] ?? null;
        $port = $_ENV['DB_PORT'] ?? null;
        $database = $_ENV['DB_DATABASE'] ?? null;
        $username = $_ENV['DB_USERNAME'] ?? null;
        $password = $_ENV['DB_PASSWORD'] ?? null;

        if (
            !$host ||
            !$port ||
            !$database ||
            !$username ||
            $password === null
        ) {
            throw new RuntimeException(
                'Configuração do banco incompleta no .env.'
            );
        }

        $caPath = __DIR__ . '/../../certificates/ca.pem';

        if (!file_exists($caPath)) {
            throw new RuntimeException(
                'Certificado CA não encontrado em: ' . $caPath
            );
        }

        $dsn = "mysql:";
        $dsn .= "host={$host};";
        $dsn .= "port={$port};";
        $dsn .= "dbname={$database};";
        $dsn .= "charset=utf8mb4;";
        $dsn .= "sslmode=verify-ca;";
        $dsn .= "sslrootcert={$caPath}";

        try {

            return new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE =>
                    PDO::ERRMODE_EXCEPTION,

                    PDO::ATTR_DEFAULT_FETCH_MODE =>
                    PDO::FETCH_ASSOC,

                    PDO::ATTR_TIMEOUT =>
                    10
                ]
            );
        } catch (PDOException $e) {

            throw new RuntimeException(
                'Erro ao conectar ao banco de dados: ' .
                    $e->getMessage(),
                0,
                $e
            );
        }
    }
}
