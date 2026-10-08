<?php

require_once '../../components/productCard.php';

$busca = trim($_GET['busca'] ?? '');

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

// Filtro temporário enquanto os produtos são fixos (depois vem do backend)
if ($busca !== '') {
    $produtos = array_filter($produtos, function ($produto) use ($busca) {
        return mb_stripos($produto['nome'], $busca) !== false;
    });
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Produtos | Código42</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <?php require_once __DIR__ . '/../../components/header.php'; ?>

    <main class="container py-5">

        <div class="mb-5">

            <h1 class="display-5 fw-bold">
                Nossos produtos
            </h1>

            <p class="lead text-muted">
                <?php if ($busca !== ''): ?>
                    Resultados para "<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>"
                <?php else: ?>
                    Encontre camisetas, xícaras e adesivos personalizados.
                <?php endif; ?>
            </p>

        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <?php if (empty($produtos)): ?>

                <div class="col-12">
                    <div class="alert alert-secondary text-center">
                        Nenhum produto encontrado.
                    </div>
                </div>

            <?php else: ?>

                <?php foreach ($produtos as $produto): ?>

                    <?php renderProductCard($produto); ?>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </main>

    <footer class="bg-dark text-white mt-5">

        <div class="container py-4 text-center">

            <p class="mb-0">
                Código42 - Produtos personalizados
            </p>

        </div>

    </footer>

</body>

</html>