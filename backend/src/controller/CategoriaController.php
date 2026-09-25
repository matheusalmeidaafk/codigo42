<?php

namespace App\Controller;

use App\Service\CategoriaService;
use Exception;

class CategoriaController
{
    private CategoriaService $service;

    public function __construct()
    {
        $this->service = new CategoriaService();
    }

    public function listar(): void
    {
        try {
            $categorias = $this->service->listar();

            http_response_code(200);

            echo json_encode(
                $categorias,
                JSON_UNESCAPED_UNICODE
            );
        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode(
                ['erro' => $e->getMessage()],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    public function getCategoriaPai() : void {
        try {
            $categorias = $this->service->getCategoriaPai();

            http_response_code(200);

            echo json_encode($categorias);
        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }
    public function getSubcategoria(int $idCategoriaPai) : void {
        try {
            $categorias = $this->service->getSubcategoria($idCategoriaPai);

            http_response_code(200);

            echo json_encode($categorias);
        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }


}
