<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <?php

    include_once __DIR__ . "/../components/cardProduto.php";

    $url = "http://localhost:8080/produtos";

    $resposta = file_get_contents($url);

    $produtos = json_decode($resposta, true);

    ?>

    <div class="container py-4">

        <div class="row g-3">

            <?php foreach ($produtos as $produto): ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                    <?php
                    CardProduto(
                        $produto['nome'],
                        (int) $produto['estrelas'],
                        (float) $produto['preco'],
                        $produto['imagem_url']
                    );
                    ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</body>

</html>