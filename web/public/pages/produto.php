<?php
$idProduto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$idProduto = is_int($idProduto) && $idProduto > 0 ? $idProduto : 0;

$categorias = [];
if (isset($_GET['categorias']) && is_string($_GET['categorias'])) {
    foreach (explode(',', $_GET['categorias']) as $idCategoria) {
        $idCategoria = trim($idCategoria);
        if (ctype_digit($idCategoria) && (int) $idCategoria > 0) {
            $categorias[] = (int) $idCategoria;
        }
    }
}
$categorias = array_values(array_unique($categorias));

$termoRelacionado = isset($_GET['termo']) && is_string($_GET['termo'])
    ? trim($_GET['termo'])
    : '';

$erroPagina = null;
if ($idProduto <= 0) {
    $erroPagina = 'Informe um ID de produto válido na URL.';
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sugestões de produtos | Código 42</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/cardProduto.css">
    <link rel="stylesheet" href="/assets/css/produtoSugestoes.css">
</head>
<body>
<main class="container py-4">
    <?php if ($erroPagina !== null): ?>
        <p class="alert alert-warning" role="alert">
            <?= htmlspecialchars($erroPagina, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php else: ?>
        <!-- Página mínima de teste da Tarefa 19. Na página de detalhes real,
             mover esta seção para o local das sugestões e preencher os data-*. -->
        <section
            id="produto-sugestoes"
            class="produto-sugestoes"
            aria-labelledby="sugestoes-titulo"
            data-produto-id="<?= $idProduto ?>"
            data-categoria-ids="<?= htmlspecialchars(implode(',', $categorias), ENT_QUOTES, 'UTF-8') ?>"
            data-termo-relacionado="<?= htmlspecialchars($termoRelacionado, ENT_QUOTES, 'UTF-8') ?>"
        >
            <h2 id="sugestoes-titulo" class="h4 produto-sugestoes-titulo">
                Você também pode gostar
            </h2>
            <p class="text-center text-secondary" data-sugestoes-status role="status" aria-live="polite">
                Carregando sugestões...
            </p>
            <div data-sugestoes-renderizado></div>
        </section>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script src="/assets/js/produtoSugestoes.js"></script>
</body>
</html>
