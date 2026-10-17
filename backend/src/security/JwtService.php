<?php

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService
{
    private string $secretKey;
    private string $algorithm = 'HS256';
    private int $expiration = 3600;

    public function __construct()
    {
        $this->secretKey = $_ENV['JWT_SECRET'];
    }

    public function gerarToken(
        int $idUsuario,
        string $email,
    ): string {

        $agora = time();

        $payload = [
            'id' => $idUsuario,
            'email' => $email,
            'iat' => $agora,
            'exp' => $agora + $this->expiration
        ];

        return JWT::encode(
            $payload,
            $this->secretKey,
            $this->algorithm
        );
    }

    public function validarToken(string $token): object
    {
        return JWT::decode(
            $token,
            new Key(
                $this->secretKey,
                $this->algorithm
            )
        );
    }
}