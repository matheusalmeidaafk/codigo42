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
}
