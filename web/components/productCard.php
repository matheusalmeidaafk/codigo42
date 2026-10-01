<?php

require_once '../../components/productCard.php';

$produtos = [

    [
        'nome' => 'Camiseta Dev',
        'descricao' => 'Camiseta personalizada para desenvolvedores.',
        'preco' => 59.90,
        'imagem' => 'https://placehold.co/600x400?text=Camiseta+Dev',
        'estoque' => 10
    ],

    [
        'nome' => 'Xícara Dev',
        'descricao' => 'Xícara personalizada para programadores.',
        'preco' => 39.90,
        'imagem' => 'https://placehold.co/600x400?text=Xicara+Dev',
        'estoque' => 5
    ],

    [
        'nome' => 'Adesivo Dev',
        'descricao' => 'Adesivo personalizado para notebooks.',
        'preco' => 9.90,
        'imagem' => 'https://placehold.co/600x400?text=Adesivo+Dev',
        'estoque' => 0
    ]

];


$termoBusca = trim($_GET['q'] ?? '');

if ($termoBusca !== '') {
    $produtos = array_filter(
        $produtos,
        fn($produto) => mb_stripos($produto['nome'], $termoBusca) !== false
            || mb_stripos($produto['descricao'], $termoBusca) !== false
    );
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Produtos | Ecommerce</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <?php require_once '../../components/header.php'; ?>

    <main class="container py-5">

        <div class="mb-5">

            <h1 class="display-5 fw-bold">
                <?= $termoBusca !== '' ? 'Resultados para &quot;' . htmlspecialchars($termoBusca, ENT_QUOTES, 'UTF-8') . '&quot;' : 'Nossos produtos' ?>
            </h1>

            <p class="lead text-muted">
                Encontre camisetas, xícaras e adesivos personalizados.
            </p>

        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <?php foreach ($produtos as $produto): ?>

                <?php renderProductCard($produto); ?>

            <?php endforeach; ?>

            <?php if (empty($produtos)): ?>
                <p class="text-muted">Nenhum produto encontrado.</p>
            <?php endif; ?>

        </div>

    </main>

    <footer class="bg-dark text-white mt-5">

        <div class="container py-4 text-center">

            <p class="mb-0">
                Ecommerce - Produtos personalizados
            </p>

        </div>

    </footer>

</body>

</html>