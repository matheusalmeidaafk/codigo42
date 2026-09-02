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

   public function criar(?string $imagemUrl, string $nome, string $descricao, string $tamanho, float $preco, int $estoque, bool $ativo) {
        if (empty($nome)) {
            throw new Exception("Nome do produto é obrigatório.");
        }
        if (empty($descricao)) {
            throw new Exception("Descricao do produto é obrigatória.");
        }
        if (empty($tamanho)) {
            throw new Exception("Tamanho do produto é obrigatório.");
        }
        if (empty($preco)) {
            throw new Exception("Preço do produto é obrigatório.");
        }
        if (empty($estoque)) {
            throw new Exception("Estoque do produto é obrigatório.");
        }

        $sql = "INSERT INTO produto (imagem_url, nome, descricao, tamanho, preco, estoque, ativo) VALUES (?, ?, ?, ?, ? , ?, ?)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$imagemUrl, $nome, $descricao, $tamanho, $preco, $estoque, $ativo]);

        $id = $this->db->lastInsertId();

        return new Produto($id, $imagemUrl, $nome, $descricao, $tamanho, $preco, $estoque, $ativo);
    }

    public function listar() : array {
        $sql = "SELECT * FROM produto";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}