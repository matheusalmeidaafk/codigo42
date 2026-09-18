<?php

namespace App\Controller;

use App\Service\FooterService;
use Exception;

class FooterController {
    private FooterService $service;

    public function __construct() {
        $this->service = new FooterService();
    }

    public function obter() : void {
        try {
            $footer = $this->service->obter();

            if ($footer === null) {
                http_response_code(404);

                echo json_encode([
                    "erro" => "Footer não configurado."
                ]);

                return;
            }

            http_response_code(200);

            echo json_encode([
                "id" => $footer->id,
                "logoUrl" => $footer->logoUrl,
                "descricao" => $footer->descricao,
            ]);

        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function salvar() : void {
        try {
            $dados = json_decode(
                file_get_contents("php://input"),
                true
            );

            $footer = $this->service->salvar(
                $dados["logoUrl"] ?? null,
                $dados["descricao"] ?? null
            );

            http_response_code(200);

            echo json_encode([
                "id" => $footer->id,
                "logoUrl" => $footer->logoUrl,
                "descricao" => $footer->descricao,
            ]);

        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }
}
