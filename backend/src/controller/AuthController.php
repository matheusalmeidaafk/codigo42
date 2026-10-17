<?php

namespace App\Controller;

use App\Service\AuthService;
use Exception;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(): void
    {
        $dados = json_decode(
            file_get_contents("php://input"),
            true
        );

        try {

            $token = $this->authService->login(
                $dados['email'],
                $dados['senha']
            );

            http_response_code(200);

            echo json_encode([
                'token' => $token
            ]);

        } catch (Exception $e) {

            http_response_code(401);

            echo json_encode([
                'erro' => $e->getMessage()
            ]);
        }
    }
}