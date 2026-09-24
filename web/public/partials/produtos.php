<?php

require_once __DIR__ . '/../../services/produtoApi.php';
require_once __DIR__ . '/../../components/carrosselProdutos.php';

$categoriaId = null;

if (isset($_GET['categoriaId'])) {

    $valorCategoria = (string) $_GET['categoriaId'];

    if (
        !ctype_digit($valorCategoria)
        || (int) $valorCategoria <= 0
    ) {
        http_response_code(400);

        echo '<div class="alert alert-danger">
                Categoria inválida.
              </div>';

        exit;
    }

    $categoriaId = (int) $valorCategoria;
}

try {

    $produtos = buscarProdutosApi($categoriaId);
} catch (Throwable $e) {

    http_response_code(500);

    echo '<div class="alert alert-danger">
            Não foi possível carregar os produtos agora.
          </div>';

    exit;
}

if (empty($produtos)) {

    echo '<div class="alert alert-warning">
            Nenhum produto encontrado.
          </div>';

    exit;
}

renderCarrosselProdutos($produtos);
