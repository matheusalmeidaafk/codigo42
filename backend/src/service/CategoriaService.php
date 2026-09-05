<?php

namespace App\Service;

use App\Config\OnlineDB;
use PDO;

class CategoriaService {

    private PDO $db;

    public function __construct() {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    public function listar() : array {
        $sql = "SELECT * FROM categoria";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    
}