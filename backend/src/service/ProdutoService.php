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

    public function criar(?string $imagemUrl, string $nome, string $descricao, float $preco, bool $ativo, bool $isAutoral)
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
        if (empty($isAutoral)) {
            throw new Exception("A autoralidade do produto é obrigatória.");
        }

        $sql = "INSERT INTO produto (imagem_url, nome, descricao, preco, ativo, is_autoral) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$imagemUrl, $nome, $descricao, $preco, $ativo, $isAutoral]);

        $id = $this->db->lastInsertId();

        return new Produto($id, $imagemUrl, $nome, $descricao, $preco, $ativo, $isAutoral);
    }

    public function listar(): array
    {
        $sql = "SELECT * FROM produto";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function filtrarCategoria($precoMin, $precoMax, $isAutoral, array $categorias = []): array
    {
        $sql = "
        SELECT p.*
        FROM produto p
        INNER JOIN produto_categoria pc
            ON pc.id_produto = p.id_produto
        WHERE p.ativo = TRUE
    ";

        $params = [];

        if (!empty($categorias)) {
            $placeholders = implode(',', array_fill(0, count($categorias), '?'));

            $sql .= "
            AND pc.id_categoria IN ($placeholders)
        ";

            $params = $categorias;
            
        }
        if (!empty($precoMin)) {
            $sql .= "AND p.preco >= ?";

            array_push($params, (float) $precoMin);
        }
        if (!empty($precoMax)) {
            $sql .= "AND p.preco <= ?";

            array_push($params, (float) $precoMax);
        }
        if ($isAutoral !== null) {
            $sql .= " AND p.is_autoral = ?";
            
            $isAutoral = filter_var(
                $isAutoral,
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );

            array_push($params, (bool) $isAutoral);
        } else if (empty($params)) {
            return $this->listar();
        }

        $sql .= " ORDER BY p.id_produto DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}