<?php

namespace App\Service;

use App\Config\OnlineDB;
use App\Model\Produto;
use Exception;
use PDO;

class ProdutoService
{
    private PDO $db;

    public function __construct()
    {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

   public function criar(string $nome, string $descricao, float $preco, bool $ativo, ?string $imagemUrl = null) {
        if (empty($nome)) {
            throw new Exception("Nome do produto é obrigatório.");
        }
        if (empty($descricao)) {
            throw new Exception("Descricao do produto é obrigatória.");
        }
        if (empty($preco)) {
            throw new Exception("Preço do produto é obrigatório.");
        }

        $sql = "INSERT INTO produto (imagem_url, nome, descricao, preco, ativo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$imagemUrl, $nome, $descricao, $preco, $ativo]);

        $id = $this->db->lastInsertId();

        return new Produto($id, $imagemUrl, $nome, $descricao, $preco, $ativo);
    }

    public function listar() : array {
        $sql = "SELECT * FROM produto";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}