<?php
// logicas
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Código 42 | Produtos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">

    <!-- Estilos do projeto -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/cardProduto.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php require_once __DIR__ . '/../../components/header.php'; ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-grow-1 py-4">
        <div class="container">
            <h1 class="mb-4">Produtos</h1>

            <!-- O conteúdo/listagem de produtos pode ser colocado aqui. -->
             
        </div>
    </main>

    <!-- FOOTER -->
    <?php require_once __DIR__ . '/../../components/footerHome.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
    <script src="/assets/js/main.js"></script>
</body>

</html>
