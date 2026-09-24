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
        $sql = "SELECT * FROM produto";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarFiltros(): array
    {
        $filtros = [];

        $sql = "
        SELECT DISTINCT tipo
        FROM produto
        WHERE ativo = 1
        AND tipo IS NOT NULL
        AND tipo <> ''
        ORDER BY tipo
    ";

        $stmt = $this->db->query($sql);

        $filtros['tipos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        $sql = "
        SELECT DISTINCT cor
        FROM produto
        WHERE ativo = 1
        AND cor IS NOT NULL
        AND cor <> ''
        ORDER BY cor
    ";

        $stmt = $this->db->query($sql);

        $filtros['cores'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        $sql = "
        SELECT DISTINCT tamanho
        FROM produto
        WHERE ativo = 1
        AND tamanho IS NOT NULL
        AND tamanho <> ''
        ORDER BY tamanho
    ";

        $stmt = $this->db->query($sql);

        $filtros['tamanhos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        $sql = "
        SELECT DISTINCT categoria
        FROM produto
        WHERE ativo = 1
        AND categoria IS NOT NULL
        AND categoria <> ''
        ORDER BY categoria
    ";

        $stmt = $this->db->query($sql);

        $filtros['categorias'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        $sql = "
        SELECT
            MIN(preco) AS precoMin,
            MAX(preco) AS precoMax
        FROM produto
        WHERE ativo = 1
    ";

        $stmt = $this->db->query($sql);

        $precos = $stmt->fetch(PDO::FETCH_ASSOC);

        $filtros['precoMin'] = (float) $precos['precoMin'];
        $filtros['precoMax'] = (float) $precos['precoMax'];


        return $filtros;
    }
}
