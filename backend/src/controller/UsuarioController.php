<?php

namespace App\Controller;

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
                $dados["imagem_url"] ?? null,
                $dados["nomeCompleto"],
                $dados["email"],
                $dados["telefone"],
                $dados["cpf"],
                $dados["senha"],
            );

            http_response_code(201);

            echo json_encode([
                "id" => $usuario->id,
                "imagem_url" => $usuario->imagem_url,
                "nomeCompleto" => $usuario->nomeCompleto,
                "email" => $usuario->email,
                "telefone" => $usuario->telefone,
                "cpf" => $usuario->cpf,
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

    public function deletar(int $id) : bool {
        return $this->service->deletar($id);
    }

    public function perfil(object $usuario): void
    {
        $perfil = $this->service->buscarPerfil((int) $usuario->id);
        http_response_code($perfil ? 200 : 404);
        echo json_encode($perfil ?: ['erro' => 'Usuário não encontrado.']);
    }

    public function atualizarPerfil(object $usuario): void
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            if (!is_array($dados)) throw new Exception('JSON inválido.');
            $perfil = $this->service->buscarPerfil((int) $usuario->id);
            if (!$perfil) {
                http_response_code(404);
                echo json_encode(['erro' => 'Usuário não encontrado.']);
                return;
            }
            $atualizado = $this->service->atualizarPerfil((int) $usuario->id,
                (string) ($dados['nome_completo'] ?? $perfil['nome_completo']),
                (string) ($dados['email'] ?? $perfil['email']),
                (string) ($dados['telefone'] ?? $perfil['telefone']));
            echo json_encode($atualizado);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }
}
