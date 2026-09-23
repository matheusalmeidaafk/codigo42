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

    public function listar(): void
    {
        try {
            $categoria = isset($_GET['categoria'])
                ? trim((string) $_GET['categoria'])
                : null;

            $categoriaId = null;

            if (isset($_GET['categoriaId'])) {
                if (
                    !ctype_digit((string) $_GET['categoriaId'])
                    || (int) $_GET['categoriaId'] <= 0
                ) {
                    throw new Exception('categoriaId inválido.');
                }

                $categoriaId = (int) $_GET['categoriaId'];
            }

            if ($categoria === '') {
                $categoria = null;
            }

            $produtos = $this->service->listar(
                $categoria,
                $categoriaId
            );

            http_response_code(200);

            echo json_encode(
                $produtos,
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
}
