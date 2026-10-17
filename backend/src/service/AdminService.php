<?php

namespace App\Service;

use App\Config\OnlineDB;
use App\Model\Admin;
use Exception;
use PDO;

class AdminService {

    private PDO $db;

    public function __construct() {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM adm WHERE email = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

    public function criar(string $email, string $senha): Admin
    {

        if (empty($email)) {
            throw new Exception("Email precisa ser informado.");
        }

        if (empty($senha)) {
            throw new Exception("senha precisa ser informada.");
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO adm (email, senha) VALUES (?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $senhaHash]);

        $id = (int) $this->db->lastInsertId();

        return new Admin($email, $senha, true, $id);
    }
}