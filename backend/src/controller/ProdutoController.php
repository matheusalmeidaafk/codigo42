<?php

namespace App\Controller;

use App\Service\ProdutoService;
use Exception;

class ProdutoController
{
    private ProdutoService $service;

    public function __construct()
    {
        $this->service = new ProdutoService();
    }

    public function criarProduto(): void
    {
        try {
            $dados = json_decode(
                file_get_contents('php://input'),
                true
            );

            if (!is_array($dados)) {
                throw new Exception('JSON inválido.');
            }

            $imagemUrl = trim((string) ($dados['imagemUrl'] ?? ''));
            $nome = trim((string) ($dados['nome'] ?? ''));
            $descricao = trim((string) ($dados['descricao'] ?? ''));
            $precoInformado = $dados['preco'] ?? null;
            $isAutoral = $dados['preco'] ?? false;

            if (!is_numeric($precoInformado)) {
                throw new Exception('Preço do produto deve ser numérico.');
            }

            $categoriaIds = $this->obterCategoriaIds($dados);

            $produto = $this->service->criar(
                $imagemUrl,
                $nome,
                $descricao,
                (float) $precoInformado,
                true,
                $isAutoral,
                $categoriaIds
            );

            http_response_code(201);

            echo json_encode(
                [
                    'id' => $produto->id,
                    'imagemUrl' => $produto->imagemUrl,
                    'nome' => $produto->nome,
                    'descricao' => $produto->descricao,
                    'preco' => $produto->preco,
                    'ativo' => $produto->ativo,
                    'categoriaIds' => $categoriaIds,
                ],
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

     public function listar() : void {
        try {
            $categorias = $_GET['categorias'] ?? '';

            $categorias = array_filter(
                array_map('intval', explode(',', $categorias))
            );

            $precoMin = $_GET['precoMin'] ?? '';
            $precoMax = $_GET['precoMax'] ?? '';
            
            $isAutoral = $_GET['autoral'] ?? null;

            $cor = $_GET['cor'] ?? '';
            $tamanhos = $_GET['tamanhos'] ?? '';

            
            $tamanhos = array_filter(
                array_map('strval', explode(',', $tamanhos))
            );

            $produtos = $this->service->filtrarCategoria($precoMin, $precoMax, $isAutoral, $cor, $tamanhos, $categorias);

            http_response_code(200);
            
            echo json_encode($produtos);

        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

    private function obterCategoriaIds(array $dados): array
    {
        $categoriaIds = [];

        if (isset($dados['categoriaId'])) {
            $categoriaIds[] = $dados['categoriaId'];
        }

        if (isset($dados['categoriaIds'])) {
            if (!is_array($dados['categoriaIds'])) {
                throw new Exception('categoriaIds deve ser uma lista.');
            }

            $categoriaIds = array_merge(
                $categoriaIds,
                $dados['categoriaIds']
            );
        }

        $categoriaIds = array_map(
            static function ($id): int {
                if (!is_numeric($id) || (int) $id <= 0) {
                    throw new Exception('ID de categoria inválido.');
                }

                return (int) $id;
            },
            $categoriaIds
        );

        return array_values(array_unique($categoriaIds));
    }
    
    public function pesquisar(string $pesquisa) : void {
        
        try {
            $produtos = $this->service->pesquisar($pesquisa);

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
