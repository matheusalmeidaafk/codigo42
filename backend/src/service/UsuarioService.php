<?php

namespace App\Service;

use App\Config\Database;
use App\Model\Usuario;
use Exception;
use PDO;

class UsuarioService {
    private PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    public function listar() : array {
        $sql = "SELECT * FROM usuario";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function criar(string $nome, string $email) : Usuario {
        if(empty($nome)) {
            throw new Exception("Nome precisa ser informado.");
            }
            
        if(empty($email)) {
            throw new Exception("Email precisa ser informado.");
        }

        $sql = "INSERT INTO usuario (nome, email) VALUES (?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nome, $email]);

        $id = (int) $this->db->lastInsertId();

        return new Usuario($nome, $email, $id);
    }

}