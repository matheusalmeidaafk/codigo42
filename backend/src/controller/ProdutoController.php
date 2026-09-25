<?php

namespace App\Controller;

use App\Service\ProdutoService;
use Exception;

class ProdutoController {
    private ProdutoService $service;

    public function __construct() {
        $this->service = new ProdutoService();
    }

    public function criarProduto() : void {
        
        try {
            $dados = json_decode(
                file_get_contents("php://input"),
                true
            );

            $produto = $this->service->criar(
                $dados["imagemUrl"],
                $dados["nome"],
                $dados["descricao"],
                $dados["preco"],
                $dados["isAutoral"],
                true
            );

            http_response_code(201);

            echo json_encode([
                "id" => $produto->id,
                "imagemUrl" => $produto->imagemUrl,
                "nome" => $produto->nome,
                "descricao" => $produto->descricao,
                "preco" => $produto->preco,
                "ativo" => $produto->ativo,
            ]);


        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

    public function listar() : void {
        try {
            $categorias = $_GET['categorias'] ?? '';

            $categorias = array_filter(
                array_map('intval', explode(',', $categorias))
            );

            $precoMin = $_GET['precoMin'] ?? '';
            $precoMax = $_GET['precoMax'] ?? '';
            
            $isAutoral = $_GET['autoral'] ?? null;



            $produtos = $this->service->filtrarCategoria($precoMin, $precoMax, $isAutoral, $categorias);

            http_response_code(200);
            
            echo json_encode($produtos);

        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

}