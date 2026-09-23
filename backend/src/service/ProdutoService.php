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

    public function criar(?string $imagemUrl, string $nome, string $descricao, float $preco, bool $ativo)
    {
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

    public function listar(): array
    {
        $sql = "
        SELECT
            p.*,
            COALESCE(ROUND(AVG(a.estrelas)), 0) AS estrelas
        FROM produto p
        LEFT JOIN avaliacao_produto a
            ON a.id_produto = p.id_produto
        WHERE p.ativo = TRUE
        GROUP BY p.id_produto
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}