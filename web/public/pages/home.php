<?php

require_once __DIR__ . '/../../components/productCard.php';

$novidades = [
    [
        'nome' => 'Camiseta Dev',
        'descricao' => 'Camiseta personalizada.',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300/181818/ffffff?text=Camiseta+Dev',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Camiseta Rock',
        'descricao' => 'Estampa exclusiva.',
        'preco' => 64.90,
        'imagem_url' => 'https://placehold.co/300x300/222222/ffffff?text=Camiseta+Rock',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Caneca Dev',
        'descricao' => 'Caneca para programadores.',
        'preco' => 39.90,
        'imagem_url' => 'https://placehold.co/300x300/f1f1f1/222222?text=Caneca+Dev',
        'categoria' => 'caneca'
    ],
    [
        'nome' => 'Adesivo Código 42',
        'descricao' => 'Adesivo para notebook.',
        'preco' => 9.90,
        'imagem_url' => 'https://placehold.co/300x300/eeeeee/222222?text=Adesivo',
        'categoria' => 'adesivo'
    ],
    [
        'nome' => 'Camiseta Samurai',
        'descricao' => 'Camiseta estampada.',
        'preco' => 69.90,
        'imagem_url' => 'https://placehold.co/300x300/202020/ffffff?text=Samurai',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Caneca Código 42',
        'descricao' => 'Caneca personalizada.',
        'preco' => 42.90,
        'imagem_url' => 'https://placehold.co/300x300/e8e8e8/222222?text=Caneca+42',
        'categoria' => 'caneca'
    ],
    [
        'nome' => 'Adesivo Dev',
        'descricao' => 'Adesivo programação.',
        'preco' => 7.90,
        'imagem_url' => 'https://placehold.co/300x300/ededed/222222?text=Dev',
        'categoria' => 'adesivo'
    ]
];

$favoritos = [
    [
        'nome' => 'Camiseta Branca',
        'descricao' => 'Modelo clássico.',
        'preco' => 59.90,
        'imagem_url' => 'https://placehold.co/300x300/f4f4f4/222222?text=Camiseta',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Caneca Code',
        'descricao' => 'Caneca personalizada.',
        'preco' => 44.90,
        'imagem_url' => 'https://placehold.co/300x300/eeeeee/222222?text=Caneca',
        'categoria' => 'caneca'
    ],
    [
        'nome' => 'Camiseta Skull',
        'descricao' => 'Estampa exclusiva.',
        'preco' => 69.90,
        'imagem_url' => 'https://placehold.co/300x300/171717/ffffff?text=Skull',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Camiseta Samurai',
        'descricao' => 'Modelo premium.',
        'preco' => 72.90,
        'imagem_url' => 'https://placehold.co/300x300/202020/ffffff?text=Samurai',
        'categoria' => 'camiseta'
    ],
    [
        'nome' => 'Caneca Verde',
        'descricao' => 'Caneca Código 42.',
        'preco' => 39.90,
        'imagem_url' => 'https://placehold.co/300x300/dfeee5/222222?text=Caneca+Verde',
        'categoria' => 'caneca'
    ],
    [
        'nome' => 'Adesivo PHP',
        'descricao' => 'Adesivo para notebook.',
        'preco' => 8.90,
        'imagem_url' => 'https://placehold.co/300x300/eeeeee/222222?text=PHP',
        'categoria' => 'adesivo'
    ]
];

function renderVitrine(
    string $titulo,
    string $id,
    array $produtos
): void {
?>

    <section
        class="vitrine mb-5"
        data-vitrine="<?= htmlspecialchars($id) ?>">

        <div
            class="d-flex
                   justify-content-between
                   align-items-end
                   flex-wrap
                   gap-2
                   mb-2">

            <h2 class="vitrine-titulo mb-0">
                <?= htmlspecialchars($titulo) ?>
            </h2>

            <div
                class="btn-group btn-group-sm filtros-produto"
                role="group"
                aria-label="Filtros de <?= htmlspecialchars($titulo) ?>">

                <button
                    type="button"
                    class="btn btn-dark filtro-produto"
                    data-categoria="todos">
                    TUDO
                </button>

                <button
                    type="button"
                    class="btn btn-outline-dark filtro-produto"
                    data-categoria="camiseta">
                    CAMISETAS
                </button>

                <button
                    type="button"
                    class="btn btn-outline-dark filtro-produto"
                    data-categoria="caneca">
                    CANECAS
                </button>

                <button
                    type="button"
                    class="btn btn-outline-dark filtro-produto"
                    data-categoria="adesivo">
                    ADESIVOS
                </button>

            </div>

        </div>

        <div class="vitrine-conteudo position-relative border">

            <button
                type="button"
                class="btn btn-light vitrine-seta vitrine-anterior rounded-0"
                aria-label="Produtos anteriores">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="vitrine-track">

                <?php foreach ($produtos as $produto): ?>

                    <?php renderProductCard($produto); ?>

                <?php endforeach; ?>

            </div>

            <button
                type="button"
                class="btn btn-light vitrine-seta vitrine-proximo rounded-0"
                aria-label="Próximos produtos">
                <i class="bi bi-chevron-right"></i>
            </button>

        </div>

    </section>

<?php
}
?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Home | Código 42</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

    <header class="border-bottom">

        <nav class="navbar navbar-expand-lg bg-white">

            <div class="container">

                <a
                    class="navbar-brand fw-bold"
                    href="#">
                    &lt;/Código42&gt;
                </a>

                <div class="d-flex gap-3 align-items-center">

                    <a
                        href="#"
                        class="text-dark text-decoration-none">
                        Sobre
                    </a>

                    <a
                        href="#"
                        class="text-dark fs-5"
                        aria-label="Minha conta">
                        <i class="bi bi-person"></i>
                    </a>

                    <a
                        href="#"
                        class="text-dark fs-5"
                        aria-label="Carrinho">
                        <i class="bi bi-cart"></i>
                    </a>

                </div>

            </div>

        </nav>

    </header>

    <main class="container py-4">

        <?php
        renderVitrine(
            'NOVIDADES',
            'novidades',
            $novidades
        );
        ?>

        <?php
        renderVitrine(
            'FAVORITOS',
            'favoritos',
            $favoritos
        );
        ?>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="../assets/js/main.js"></script>

</body>

</html>