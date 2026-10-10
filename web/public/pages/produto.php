<?php

require_once __DIR__ . '/../../services/produtoApi.php';
require_once __DIR__ . '/../../components/navProdutos.php';
require_once __DIR__ . '/../../components/carrosselProdutos.php';

$produtos = [];
$categorias = [];
$erroApi = null;

$categoriaSelecionada = null;

if (
    isset($_GET['categorias'])
    && ctype_digit((string) $_GET['categorias'])
    && (int) $_GET['categorias'] > 0
) {
    $categoriaSelecionada = (int) $_GET['categorias'];
}

try {

    $categorias = buscarCategoriasApi();

    $produtos = buscarProdutosApi(
        $categoriaSelecionada
    );
} catch (Throwable $e) {

    $erroApi = 'Não foi possível carregar os produtos agora.';
}

?>

<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Código 42</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- CSS geral -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- CSS do banner -->
    <link rel="stylesheet" href="/assets/css/banner.css">

    <!-- CSS do card -->
    <link rel="stylesheet" href="/assets/css/cardProduto.css">

    <!-- CSS do produto -->
    <link rel="stylesheet" href="/assets/css/produtos.css">
</head>

<body>

    <!-- HEADER -->
    <?php require_once __DIR__ . '/../../components/header.php'; ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="">

        <div class="row g-4 mx-auto" style="max-width: 1000px;">

            <section class="col-12 col-md-7">

                <div class="row g-1">

                    <div class="col-2 col-sm-2">
                        <div class="d-flex flex-column gap-1">

                            <?php
                            $imagem = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCFvF40vklp0Go1-gC0qcrVYZJPbnGrZw7R5hmNh608Isv6ytN_NI6zg90&s=10';

                            for ($i = 0; $i < 4; $i++):
                                ?>
                                <img src="<?= $imagem ?>" alt="Miniatura <?= $i + 1 ?> da camiseta"
                                    class="img-fluid border border-secondary-subtle miniatura-produto <?= $i === 0 ? 'border-2 border-dark' : '' ?>"
                                    data-indice="<?= $i ?>">
                            <?php endfor; ?>

                        </div>
                    </div>

                    <div class="col-10 col-sm-10">

                        <div class="position-relative border border-secondary-subtle">

                            <img src="<?= $imagem ?>" alt="Camiseta" id="imagemPrincipal"
                                class="img-fluid w-100 imagem-produto">

                            <!-- Seta esquerda -->
                            <button type="button"
                                class="btn position-absolute start-0 top-50 translate-middle-y border-0 p-1"
                                id="imagemAnterior" aria-label="Imagem anterior">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                                </svg>
                            </button>

                            <!-- Seta direita -->
                            <button type="button"
                                class="btn position-absolute end-0 top-50 translate-middle-y border-0 p-1"
                                id="proximaImagem" aria-label="Próxima imagem">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                    viewBox="0 0 16 16" style="transform: rotate(180deg);">
                                    <path
                                        d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                                </svg>
                            </button>

                        </div>

                    </div>

                </div>

                <div class="mt-3 small">

                    <p>
                        Apresentamos nossas camisetas produzidas com todo o cuidado que você merece:
                        malha 100% algodão fio 30.1, de toque macio e confortável. O tecido é de alta
                        qualidade, garantindo resistência, caimento perfeito e aquela sensação de
                        leveza durante o uso.
                    </p>

                    <p class="mb-1">Destaques da peça:</p>

                    <ul class="ps-4 mb-0">
                        <li>Produzida em algodão hidrantado, que proporciona maior durabilidade.</li>
                        <li>Estampa no modelo DTF, com cores vivas e excelente fixação.</li>
                        <li>Corte moderno e versátil, que valoriza o corpo sem perder o conforto.</li>
                        <li>Disponível na cor preta e em diversos tamanhos.</li>
                    </ul>

                </div>

            </section>

            <section class="col-12 col-md-4 d-flex flex-column justify-content-start">

                <h1 class="h3 mb-2">
                    Camiseta Hollow Knight
                </h1>

                <div class="mb-3">
                    <p class="small text-secondary text-decoration-line-through mb-1">
                        De: R$ 150,00
                    </p>

                    <p class="mb-0">
                        Por: <strong class="fs-5">R$ 100,00</strong>
                    </p>
                </div>

                <fieldset class="border border-secondary rounded p-2 mb-3 align-self-center w-75">

                    <legend class="float-none w-auto px-1 fs-6 mb-1">
                        Tamanhos
                    </legend>

                    <div class="row row-cols-4 g-1">

                        <?php foreach (['P', 'PP', 'M', 'G', 'GG', '3G', '4G'] as $i => $tamanho): ?>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input tamanho" type="checkbox" value="<?= $tamanho ?>"
                                        id="tamanho<?= $i ?>">

                                    <label class="form-check-label small" for="tamanho<?= $i ?>">
                                        <?= $tamanho ?>
                                    </label>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>

                    <div id="avisoTamanho" class="text-danger small d-none mt-2">
                        Selecione pelo menos um tamanho.
                    </div>

                </fieldset>

                <fieldset class="border border-secondary rounded p-2 mb-3 align-self-center w-75">

                    <legend class="float-none w-auto px-1 fs-6 mb-1">
                        Quantidade
                    </legend>

                    <div class="input-group input-group-sm mx-auto" style="max-width: 150px;">

                        <button class="btn btn-outline-secondary" type="button" id="diminuir">
                            −
                        </button>

                        <input type="number" class="form-control text-center" id="quantidade" value="1" min="1"
                            max="99">

                        <button class="btn btn-outline-secondary" type="button" id="aumentar">
                            +
                        </button>

                    </div>

                </fieldset>

                <div class="d-flex align-self-center justify-content-between gap-2 w-75">

                    <button type="button" class="btn btn-outline-secondary border-success text-dark align-self-center"
                        id="adicionarCarrinho">
                        Adicionar ao carrinho
                    </button>

                    <button type="button" class="btn btn-success align-self-center" id="comprar">
                        Comprar
                    </button>

                </div>
                <a href="#parceiros" class="small link-primary text-center align-self-center mt-1">
                    Descubra como sua compra ajuda artistas parceiros
                </a>

                <div class="card border-0 bg-body-secondary mt-3 w-75 align-self-center">

                    <div class="card-body p-2">

                        <h2 class="h5 mb-1">
                            Entrega via Delivery
                        </h2>

                        <p class="small mb-0" style="font-family: Inter, sans-serif">
                            Todos os nossos produtos são enviados exclusivamente via delivery.
                            Temos endereço físico, mas
                            <strong>não realizamos retiradas no local.</strong>
                        </p>

                    </div>

                </div>

            </section>

        </div>

    </main>


    <!-- FOOTER -->
    <?php require_once __DIR__ . '/../../components/footerHome.php'; ?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>

    <script src="/assets/js/main.js"></script>

    <script src="/assets/js/produto.js"></script>

</body>

</html>