<?php

namespace App\Service;

use App\Config\Database;
use App\Config\OnlineDB;
use App\Model\Usuario;
use Exception;
use PDO;

class UsuarioService {
    private PDO $db;

    public function __construct() {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    public function listar() : array {
        $sql = "SELECT * FROM usuario";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    public function findByEmail(string $email) {
        $sql = "SELECT * FROM usuario WHERE email = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar(?string $imgperfilurl = null, string $nomeCompleto, string $email, string $telefone, string $cpf, string $senha) : Usuario {
        if(empty($nomeCompleto)) {
            throw new Exception("Nome precisa ser informado.");
        }
            
        if(empty($email)) {
            throw new Exception("Email precisa ser informado.");
        }
           
        if(empty($telefone)) {
            throw new Exception("Telefone precisa ser informado.");
        }
           
        if(empty($cpf)) {
            throw new Exception("CPF precisa ser informado.");
        }

        if(empty($senha)) {
            throw new Exception("senha precisa ser informada.");
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (imgperfilurl, nome_completo, email, telefone, cpf, senha, ativo) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$imgperfilurl, $nomeCompleto, $email, $telefone, $cpf, $senhaHash, true]);

        $id = (int) $this->db->lastInsertId();

        return new Usuario($id, $imgperfilurl, $nomeCompleto, $email, $telefone, $cpf, $senha, true);
    }

}