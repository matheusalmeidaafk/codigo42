<?php
require_once __DIR__ . '/../components/productCard.php';

$novidades = [
    [
        'nome' => 'Camiseta Metal',
        'descricao' => 'Camiseta oversized',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+1',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Camiseta Rock',
        'descricao' => 'Estampa exclusiva',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+2',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Camiseta Nirvana',
        'descricao' => 'Modelo clássico',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+3',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Camiseta Samurai',
        'descricao' => 'Camiseta premium',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+4',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Caneca Verde',
        'descricao' => 'Caneca personalizada',
        'preco' => 39.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+5',
        'categoria' => 'caneca'
    ],
];

$favoritos = [
    [
        'nome' => 'Camiseta Branca',
        'descricao' => 'Modelo favorito',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+6',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Caneca Preta',
        'descricao' => 'Caneca clássica',
        'preco' => 39.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+7',
        'categoria' => 'caneca'
    ],
    [
        'nome' => 'Camiseta Skull',
        'descricao' => 'Estampa caveira',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+8',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Camiseta Samurai',
        'descricao' => 'Modelo premium',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+9',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Caneca Verde',
        'descricao' => 'Caneca exclusiva',
        'preco' => 39.90,
        'imagem_url' => 'https://placehold.co/300x300?text=Produto+10',
        'categoria' => 'caneca'
    ],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código 42</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php require __DIR__ . '/../components/navHome.php'; ?>

    <main class="container py-4">

        <?php renderProductSection('NOVIDADES', 'novidades', $novidades); ?>
        <?php renderProductSection('FAVORITOS', 'favoritos', $favoritos); ?>

    </main>

    <script src="assets/js/main.js"></script>
</body>

</html>