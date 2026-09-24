<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Produtos</title>

    <link rel="stylesheet" href="./assets/css/cardProduto.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <?php

    include_once __DIR__ . "/../components/cardProduto.php";

    $url = "http://app/produtos";

    $resposta = @file_get_contents($url);

    $produtos = [];

    if ($resposta !== false) {
        $dados = json_decode($resposta, true);

        if (is_array($dados)) {
            $produtos = $dados;
        }
    }

    ?>

    <div class="container py-4">

        <div class="row g-3">

            <?php if (empty($produtos)): ?>

                <div class="col-12">
                    <div class="alert alert-warning">
                        Nenhum produto encontrado.
                    </div>
                </div>

            <?php else: ?>

                <?php foreach ($produtos as $produto): ?>

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                        <?php
                        CardProduto(
                            $produto['nome'],
                            (int) $produto['estrelas'],
                            (float) $produto['preco'],
                            (float) $produto['preco_final'],
                            isset($produto['porcentagem_desconto'])
                            ? (float) $produto['porcentagem_desconto']
                            : null,
                            $produto['imagem_url']
                        );
                        ?>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>

</body>

</html>
```