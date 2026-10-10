<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Codigo42</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link
        rel="stylesheet"
        href="./assets/css/filtros.css">
    <link
        rel="stylesheet"
        href="./assets/css/style.css">

</head>

<body>

    <div class="container">

        <div class="row">

            <aside class="col-md-3">

                <?php
                include __DIR__ . '/../components/filtrosProdutos.php';
                ?>

            </aside>

            <main
                class="col-md-9"
                id="produtos-container">

                <?php

                require_once __DIR__
                    . '/../components/carrosselProdutos.php';

                if (empty($produtos)):

                ?>

                    <div class="alert alert-secondary">

                        Nenhum produto encontrado
                        com os filtros selecionados.

                    </div>

                <?php else: ?>


                    <?php foreach ($produtos as $produto): ?>

                        <?php
                        CardProduto(
                            $produto['nome'],
                            (int) ($produto['estrelas'] ?? 0),
                            (float) ($produto['preco'] ?? 0),
                            (float) (
                                $produto['preco_final']
                                ?? $produto['preco']
                                ?? 0
                            ),
                            isset($produto['porcentagem_desconto'])
                                ? (float) $produto['porcentagem_desconto']
                                : null,
                            $produto['imagem_url']
                                ?? 'https://placehold.co/600x600?text=Sem+Imagem'
                        );
                        ?>

                    <?php endforeach; ?>

                <?php endif; ?>

            </main>

        </div>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="./assets/js/filtros.js"></script>

</body>

</html>