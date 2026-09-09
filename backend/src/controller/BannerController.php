<?php

namespace App\Controller;

use App\Service\BannerService;
use Exception;

class BannerController
{
    private BannerService $service;

    public function __construct()
    {
        $this->service = new BannerService();
    }

    public function criarBanner(): void
    {
        try {

            $dados = json_decode(
                file_get_contents("php://input"),
                true
            );

            if (!$dados) {
                throw new Exception("Dados inválidos.");
            }

            $banner = $this->service->criar(
                $dados["bannerUrl"] ?? "",
                $dados["linkUrl"] ?? null,
                $dados["ativo"] ?? true
            );

            http_response_code(201);

            echo json_encode([
                "id" => $banner->id,
                "bannerUrl" => $banner->bannerUrl,
                "linkUrl" => $banner->linkUrl,
                "ativo" => $banner->ativo
            ]);

        } catch (Exception $e) {

            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function listar(): void
    {
        try {

            $banners = $this->service->listar();

            http_response_code(200);

            echo json_encode($banners);

        } catch (Exception $e) {

            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function deletar(int $id): void
    {
        try {

            $this->service->deletar($id);

            http_response_code(204);

        } catch (Exception $e) {

            http_response_code(404);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }
}
