<?php

require_once __DIR__ . '/../../services/produtoApi.php';

// Parâmetros opcionais usados pela seção de sugestões.
$categoriasSugestoes = [];
if (isset($_GET['categorias']) && is_string($_GET['categorias'])) {
    foreach (explode(',', $_GET['categorias']) as $categoriaId) {
        $categoriaId = trim($categoriaId);
        if (ctype_digit($categoriaId) && (int) $categoriaId > 0) {
            $categoriasSugestoes[] = (int) $categoriaId;
        }
    }
}
$categoriasSugestoes = array_values(array_unique($categoriasSugestoes));

$termoRelacionadoSugestoes = isset($_GET['termo']) && is_string($_GET['termo'])
    ? trim($_GET['termo'])
    : '';


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$produto = null;
$erroApi = null;

try {
    if (!$id || $id <= 0) {
        http_response_code(400);
        throw new RuntimeException('Produto inválido.');
    }

    $produto = buscarProdutoPorIdApi($id);

    if ($produto === null) {
        http_response_code(404);
    }
} catch (Throwable $e) {
    if (http_response_code() < 400) {
        http_response_code(500);
    }

    $erroApi = 'Não foi possível carregar este produto.';
}

$imagem = $produto['variacao_imagem_url']
    ?? $produto['imagem_url']
    ?? null;

$nome = $produto['nome'] ?? 'Produto indisponível';
$descricao = $produto['descricao'] ?? '';
$precoOriginal = isset($produto['preco'])
    ? (float) $produto['preco']
    : null;

$precoFinal = isset($produto['preco_final'])
    ? (float) $produto['preco_final']
    : $precoOriginal;

$porcentagemDesconto = isset($produto['porcentagem_desconto'])
    ? (float) $produto['porcentagem_desconto']
    : null;

$temDesconto = $porcentagemDesconto !== null
    && $porcentagemDesconto > 0;

$tamanhos = $produto['tamanhos'] ?? [];

if (!is_array($tamanhos)) {
    $tamanhos = [];
}

$tamanhos = array_values(array_unique(array_filter(
    $tamanhos,
    fn($tamanho) => is_string($tamanho) && trim($tamanho) !== ''
)));

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

    <!-- CSS do banner -->
    <link rel="stylesheet" href="/assets/css/banner.css">

    <!-- CSS do card -->
    <link rel="stylesheet" href="/assets/css/cardProduto.css">

    <!-- CSS do produto -->
    <link rel="stylesheet" href="/assets/css/produto.css">
    <!-- CSS da seção de sugestões; mantém os estilos da página original. -->
    <link rel="stylesheet" href="/assets/css/produtoSugestoes.css">
<link rel="stylesheet" href="/assets/css/design.css">
</head>

<body class="pagina-produto">

    <!-- HEADER -->
    <?php require_once __DIR__ . '/../../components/header.php'; ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="produto-detalhe">
        <nav class="caminho" aria-label="Navegação"><a href="/pages/home.php">Home</a> / <a href="/pages/produtos.php">Produtos</a> / <?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?></nav>

        <div class="row g-4 mx-auto" style="max-width: 1000px;">

            <section class="col-12 col-md-7 px-0">

                <div class="row g-1">


                    <?php if (!empty($imagem)): ?>
                        <div class="col-2 col-sm-2">
                            <div class="miniaturas-container w-100">
                                <img src="<?= htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                                    class="img-fluid border border-secondary-subtle miniatura-produto border-2 border-dark"
                                    data-indice="0">
                            </div>
                        </div>

                        <div class="col-10 col-sm-10 h-100">
                            <div class="area-imagem-produto border border-secondary-subtle">
                                <img src="<?= htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>" id="imagemPrincipal"
                                    class="img-fluid w-100 imagem-produto">
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-12">
                            <div
                                class="area-imagem-produto border border-secondary-subtle d-flex align-items-center justify-content-center">
                                <p class="text-secondary">Imagem indisponível</p>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>


                <div class="mt-3">
                    <?php if (trim($descricao) !== ''): ?>
                        <p>
                            <?= nl2br(htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8')) ?>
                        </p>
                    <?php else: ?>
                        <p class="text-secondary">Descrição não disponível.</p>
                    <?php endif; ?>
                </div>


            </section>

            <section class="col-12 col-md-4 d-flex flex-column justify-content-start px-0">

                <h1 class="h3 mb-2 align-self-center w-75">
                    <?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>
                </h1>

                <div class="mb-2 align-self-center w-75">
                    <?php if ($precoFinal !== null): ?>

                        <?php if ($temDesconto): ?>
                            <p class="mb-0 text-secondary text-decoration-line-through">
                                De: R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                            </p>

                            <p class="mb-0">
                                <span class="text-success fw-semibold">Por:</span>
                                <strong class="fs-4">
                                    R$ <?= number_format($precoFinal, 2, ',', '.') ?>
                                </strong>
                            </p>
                        <?php else: ?>
                            <p class="mb-0">
                                <strong class="fs-4">
                                    R$ <?= number_format($precoFinal, 2, ',', '.') ?>
                                </strong>
                            </p>
                        <?php endif; ?>

                    <?php else: ?>
                        <p class="text-secondary">Preço indisponível</p>
                    <?php endif; ?>
                </div>

                <fieldset class="border rounded p-3 mb-3 align-self-center w-75">
                    <legend class="float-none w-auto fs-6">Tamanho e cor</legend>
                    <label for="variacaoProduto" class="form-label">Escolha uma opção</label>
                    <select id="variacaoProduto" class="form-select">
                        <option value="">Selecione...</option>
                        <?php foreach (($produto['variacoes'] ?? []) as $v): ?>
                        <option value="<?= (int) $v['id_variacao'] ?>" data-estoque="<?= (int) $v['estoque'] ?>" <?= (int) $v['estoque'] <= 0 ? 'disabled' : '' ?>><?= htmlspecialchars(implode(' / ', array_filter([$v['tamanho'], $v['cor']])) ?: 'Único', ENT_QUOTES, 'UTF-8') ?><?= (int) $v['estoque'] <= 0 ? ' — Esgotado' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p id="avisoTamanho" class="text-danger small d-none mt-2" role="alert">Selecione uma opção disponível.</p>
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

                <div class="d-flex align-self-center justify-content-between w-75">

                    <button type="button"
                        class="btn btn-compra btn-outline-secondary border-success text-dark align-self-center"
                        id="adicionarCarrinho"
                        data-id="<?= (int) ($produto['id_produto'] ?? $produto['id'] ?? $id) ?>"
                        data-nome="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                        data-preco-original="<?= htmlspecialchars((string) ($precoOriginal ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                        data-preco="<?= htmlspecialchars((string) ($precoFinal ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                        data-imagem="<?= htmlspecialchars((string) ($imagem ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        Adicionar ao carrinho
                    </button>

                    <button type="button" class="btn btn-compra btn-success align-self-center" id="comprar">
                        Comprar
                    </button>

                </div>
                <?php if ($produto !== null): ?>
                <button type="button" id="salvarFavorito" class="btn btn-outline-dark align-self-center mt-2"
                    data-id="<?= (int) ($produto['id_produto'] ?? $produto['id'] ?? $id) ?>"
                    data-nome="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>">
                    ♡ Salvar nos favoritos
                </button>
                <?php endif; ?>
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

        <!-- Sugestões: adicionadas sem substituir o conteúdo original da página. -->
        <section
            id="produto-sugestoes"
            class="produto-sugestoes container my-5"
            aria-labelledby="sugestoes-titulo"
            data-produto-id="<?= (int) ($produto['id_produto'] ?? $produto['id'] ?? $id) ?>"
            data-categoria-ids="<?= htmlspecialchars(implode(',', $categoriasSugestoes), ENT_QUOTES, 'UTF-8') ?>"
            data-termo-relacionado="<?= htmlspecialchars($termoRelacionadoSugestoes, ENT_QUOTES, 'UTF-8') ?>"
        >
            <h2 id="sugestoes-titulo" class="h4 produto-sugestoes-titulo">
                Você também pode gostar
            </h2>
            <p class="text-center text-secondary" data-sugestoes-status role="status" aria-live="polite">
                Carregando sugestões...
            </p>
            <div data-sugestoes-renderizado></div>
        </section>

    </main>


    <!-- FOOTER -->
    <?php require_once __DIR__ . '/../../components/footerHome.php'; ?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>

    <script src="/assets/js/main.js"></script>

    <script src="/assets/js/produto.js"></script>
    <!-- Inicializa o carrossel também quando ele é carregado dinamicamente. -->
    <script src="/assets/js/carrosselResponsivo.js"></script>
    <script src="/assets/js/produtoSugestoes.js"></script>
    <script src="/assets/js/favoritos.js"></script>

</body>

</html>
