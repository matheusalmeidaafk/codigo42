<?php

namespace App\Service;

use App\Config\OnlineDB;
use App\Model\Usuario;
use Exception;
use PDO;

class UsuarioService
{
    private PDO $db;

    public function __construct()
    {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    public function listar(): array
    {
        $sql = "SELECT id_usuario, imagem_url, nome_completo, email, telefone, cpf, ativo FROM usuario";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM usuario WHERE email = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar(?string $imagem_url, string $nomeCompleto, string $email, string $telefone, string $cpf, string $senha): Usuario
    {
        if (empty($nomeCompleto)) {
            throw new Exception("Nome precisa ser informado.");
        }

        if (empty($email)) {
            throw new Exception("Email precisa ser informado.");
        }

        if (empty($telefone)) {
            throw new Exception("Telefone precisa ser informado.");
        }

        if (empty($cpf)) {
            throw new Exception("CPF precisa ser informado.");
        }

        if (empty($senha)) {
            throw new Exception("senha precisa ser informada.");
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (imagem_url, nome_completo, email, telefone, cpf, senha, ativo) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$imagem_url, $nomeCompleto, $email, $telefone, $cpf, $senhaHash, true]);

        $id = (int) $this->db->lastInsertId();

        return new Usuario($nomeCompleto, $email, $telefone, $cpf, '', true, $id, $imagem_url);
    }

    public function buscarPerfil(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id_usuario, imagem_url, nome_completo, email, telefone, cpf FROM usuario WHERE id_usuario = ? AND ativo = 1');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function atualizarPerfil(int $id, string $nome, string $email, string $telefone): ?array
    {
        if (strlen(trim($nome)) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Informe nome e e-mail válidos.');
        }
        $stmt = $this->db->prepare('UPDATE usuario SET nome_completo = ?, email = ?, telefone = ? WHERE id_usuario = ? AND ativo = 1');
        $stmt->execute([trim($nome), trim($email), trim($telefone), $id]);
        return $this->buscarPerfil($id);
    }

    public function deletar(int $id): bool {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

}
