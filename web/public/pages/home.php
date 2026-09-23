<?php

require_once __DIR__ . '/../../components/carrosselProdutos.php';

$url = 'http://app/produtos';

$resposta = @file_get_contents($url);

$produtos = [];
$erroProdutos = null;

if ($resposta === false) {

    $erroProdutos = 'Não foi possível carregar os produtos.';
} else {

    $dados = json_decode($resposta, true);

    if (!is_array($dados)) {

        $erroProdutos = 'Resposta inválida do servidor.';
    } elseif (isset($dados['erro'])) {

        $erroProdutos = $dados['erro'];
    } else {

        $produtos = $dados;
    }
}
?>

<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    <?php if ($erroProdutos !== null): ?>

        <div class="container py-4">

            <div class="alert alert-danger">
                <?= htmlspecialchars($erroProdutos) ?>
            </div>

        </div>

    <?php elseif (empty($produtos)): ?>

        <div class="container py-4">

            <div class="alert alert-warning">
                Nenhum produto encontrado.
            </div>

        </div>

    <?php else: ?>

        <?php renderCarrosselProdutos($produtos); ?>

    <?php endif; ?>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous">
    </script>

</body>

</html>