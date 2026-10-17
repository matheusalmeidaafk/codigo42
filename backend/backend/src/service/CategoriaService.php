<?php

namespace App\Service;

use App\Config\OnlineDB;
use PDO;

class CategoriaService
{
    private PDO $db;

    public function __construct()
    {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    public function listar(): array
    {
        $sql = <<<'SQL'
            SELECT
                id_categoria,
                nome,
                id_categoria_pai
            FROM categoria
            ORDER BY nome
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $categorias = $stmt->fetchAll();

        foreach ($categorias as &$categoria) {
            $categoria['id_categoria'] = (int) $categoria['id_categoria'];

            $categoria['id_categoria_pai'] =
                $categoria['id_categoria_pai'] !== null
                    ? (int) $categoria['id_categoria_pai']
                    : null;
        }

        unset($categoria);

        return $categorias;
    }
    
    public function getCategoriaPai() : array {
        $sql = "SELECT * FROM categoria WHERE id_categoria_pai is null";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    
    public function getSubcategoria(int $idCategoriaPai) : array {
        $sql = "SELECT * FROM categoria WHERE id_categoria_pai = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCategoriaPai]);

        return $stmt->fetchAll();
    }
    
}
