<?php

require_once __DIR__ . '/../../services/produtoApi.php';
require_once __DIR__ . '/../../components/navProdutos.php';
require_once __DIR__ . '/../../components/carrosselProdutos.php';

$produtos = [];
$categorias = [];
$erroApi = null;

$categoriaSelecionada = null;

if (
    isset($_GET['categoriaId'])
    && ctype_digit((string) $_GET['categoriaId'])
    && (int) $_GET['categoriaId'] > 0
) {
    $categoriaSelecionada =
        (int) $_GET['categoriaId'];
}

try {

    $categorias = buscarCategoriasApi();

    $produtos = buscarProdutosApi(
        $categoriaSelecionada
    );
} catch (Throwable $e) {

    $erroApi =
        'Não foi possível carregar os produtos agora.';
}

?>

<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Código 42</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link
        rel="stylesheet"
        href="/assets/css/style.css">

</head>

<body>

    <main class="py-4">

        <section class="container-fluid px-0">

            <div
                class="mx-auto w-100"
                style="max-width: 1416px;">

                <div
                    class="vitrine-largura d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

                    <h2 class="fs-2 fw-normal lh-1 mb-0">
                        PRODUTOS
                    </h2>

                    <?php
                    renderNavProdutos(
                        $categorias,
                        $categoriaSelecionada
                    );
                    ?>

                </div>

                <div id="produtos-container">

                    <?php if ($erroApi !== null): ?>

                        <div class="alert alert-danger">

                            <?= htmlspecialchars(
                                $erroApi,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                    <?php elseif (empty($produtos)): ?>

                        <div class="alert alert-warning">
                            Nenhum produto encontrado.
                        </div>

                    <?php else: ?>

                        <?php renderCarrosselProdutos($produtos); ?>

                    <?php endif; ?>

                </div>

            </div>

        </section>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous">
    </script>

    <script src="/assets/js/main.js"></script>

</body>

</html>