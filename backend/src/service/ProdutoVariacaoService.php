<?php

namespace App\Service;

use App\Config\OnlineDB;
use PDO;

class ProdutoVariacaoService {
    private PDO $db;

    public function __construct() {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }


    public function getVariacaoProduto(int $idProduto) : array {
        $sql = "SELECT * FROM produto_variacao WHERE id_produto = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idProduto]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}