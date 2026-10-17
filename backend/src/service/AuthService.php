<?php

namespace App\Service;

use App\Service\UsuarioService;
use App\Security\JwtService;
use Exception;

class AuthService
{
    private UsuarioService $usuarioService;

    private AdminService $adminService;

    private JwtService $jwtService;

    public function __construct()
    {
        $this->usuarioService = new UsuarioService();
        $this->adminService = new AdminService();
        $this->jwtService = new JwtService();
    }

    public function login(
        string $email,
        string $senha
    ): string {

        $usuario = $this->usuarioService
            ->findByEmail($email);

        if (!$usuario) {
            throw new Exception("Email ou senha inválidos.");
        }
        
        if (!password_verify($senha, $usuario['senha'])) {
            throw new Exception("Email ou senha inválidos.");
        }

        if (!$usuario['ativo']) {
            throw new Exception("Usuário inativo.");
        }

        return $this->jwtService->gerarToken(
            (int) $usuario['id_usuario'],
            $usuario['email'],
        );
    }   

    public function loginAdmin(
        string $email,
        string $senha
    ): string {

        $admin = $this->adminService
            ->findByEmail($email);

        if (!$admin) {
            throw new Exception("Email ou senha inválidos.");
        }
        
        if (!password_verify($senha, $admin['senha'])) {
            throw new Exception("Email ou senha inválidos.");
        }

        if (!$admin['ativo']) {
            throw new Exception("Usuário inativo.");
        }

        return $this->jwtService->gerarTokenAdm(
            (int) $admin['id_adm'],
            $admin['email'],
        );
    }   


}