<?php

namespace App\Controller;

use App\Service\CategoriaService;

class CategoriaController {
    private CategoriaService $service;

    public function __construct() {
        $this->service = new CategoriaService();
    }

    public function listar() {
        try {
            $categorias = $this->service->listar();

            http_response_code(200);

            echo json_encode($categorias);
        } catch (\Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }
}