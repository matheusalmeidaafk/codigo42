<?php

namespace App\Middleware;

use App\Security\JwtService;
use Exception;

class AuthMiddleware
{
    private JwtService $jwtService;

    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    public function autenticar(): object
    {
        $headers = getallheaders();

        $authorization = $headers['Authorization']
            ?? $headers['authorization']
            ?? null;

        if (!$authorization) {
            http_response_code(401);

            echo json_encode([
                'erro' => 'Token não informado.'
            ]);

            exit;
        }

        if (!preg_match(
            '/Bearer\s+(.+)/i',
            $authorization,
            $matches
        )) {

            http_response_code(401);

            echo json_encode([
                'erro' => 'Formato do token inválido.'
            ]);

            exit;
        }

        $token = $matches[1];

        try {

            return $this->jwtService
                ->validarToken($token);

        } catch (Exception $e) {

            http_response_code(401);

            echo json_encode([
                'erro' => 'Token inválido ou expirado.'
            ]);

            exit;
        }
    }
}