<?php

namespace App\controller;

use App\Service\UsuarioService;
use Exception;

class UsuarioController {
    private UsuarioService $service;

    public function __construct() {
        $this->service = new UsuarioService();
    }

    public function criar() : void {
        try {
            $dados = json_decode(
                file_get_contents("php://input"),
                true
            );

            $usuario = $this->service->criar(
                $dados["nome"],
                $dados["email"]
            );

            http_response_code(201);

            echo json_encode([
                "id" => $usuario->id,
                "nome" => $usuario->nome,
                "email" => $usuario->email,
            ]);


        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function listar() : void {
        try {
            $usuarios = $this->service->listar();

            http_response_code(200);
            
            echo json_encode($usuarios);

        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }
}