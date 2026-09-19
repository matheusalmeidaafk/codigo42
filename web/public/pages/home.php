<?php

require_once __DIR__ . '/../../services/produtoApi.php';
require_once __DIR__ . '/../../components/productCard.php';

$produtos = [];
$categorias = [];
$erroApi = null;

try {
    $produtos = buscarProdutosApi();
    $categorias = buscarCategoriasApi();
} catch (Throwable $e) {
    $erroApi = 'Não foi possível carregar os produtos agora.';
}

$novidades = $produtos;

$favoritos = [];

function normalizarTexto(string $texto): string
{
    $texto = mb_strtolower(trim($texto), 'UTF-8');

    $semAcento = iconv(
        'UTF-8',
        'ASCII//TRANSLIT//IGNORE',
        $texto
    );

    return $semAcento !== false
        ? strtolower($semAcento)
        : $texto;
}

function obterCategoriasFiltro(array $categorias): array
{
    $ordemDesejada = [
        'camisetas' => 'CAMISETAS',
        'canecas' => 'CANECAS',
        'adesivos' => 'ADESIVOS',
    ];

    $categoriasPorNome = [];

    foreach ($categorias as $categoria) {
        if (($categoria['id_categoria_pai'] ?? null) !== null) {
            continue;
        }

        $idCategoria = (int) ($categoria['id_categoria'] ?? 0);
        $nomeCategoria = (string) ($categoria['nome'] ?? '');
        $nomeNormalizado = normalizarTexto($nomeCategoria);

        if ($idCategoria <= 0 || !isset($ordemDesejada[$nomeNormalizado])) {
            continue;
        }

        $categoriasPorNome[$nomeNormalizado] = [
            'id' => $idCategoria,
            'label' => $ordemDesejada[$nomeNormalizado],
        ];
    }

    $resultado = [];

    foreach ($ordemDesejada as $nome => $label) {
        if (isset($categoriasPorNome[$nome])) {
            $resultado[] = $categoriasPorNome[$nome];
        }
    }

    return $resultado;
}

$categoriasFiltro = obterCategoriasFiltro($categorias);

function renderVitrine(
    string $titulo,
    string $id,
    array $produtos,
    array $categoriasFiltro,
    string $mensagemVazia = 'Nenhum produto disponível.'
): void {
?>

    <section
        class="vitrine mb-5"
        data-vitrine="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">

        <div
            class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-2">

            <h2 class="vitrine-titulo mb-0">
                <?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>
            </h2>

            <div
                class="btn-group btn-group-sm filtros-produto"
                role="group"
                aria-label="Filtros de <?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>">

                <button
                    type="button"
                    class="btn btn-dark filtro-produto"
                    data-categoria-id="todos">
                    TUDO
                </button>

                <?php foreach ($categoriasFiltro as $categoria): ?>
                    <button
                        type="button"
                        class="btn btn-outline-dark filtro-produto"
                        data-categoria-id="<?= (int) $categoria['id'] ?>">
                        <?= htmlspecialchars($categoria['label'], ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>

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

                <?php if ($produtos !== []): ?>

                    <?php foreach ($produtos as $produto): ?>
                        <?php renderProductCard($produto); ?>
                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="text-secondary small mb-0 py-4 px-2 vitrine-vazia">
                        <?= htmlspecialchars($mensagemVazia, ENT_QUOTES, 'UTF-8') ?>
                    </p>

                <?php endif; ?>

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

                

            </div>
        </nav>
    </header>

    <main class="container py-4">

        <?php if ($erroApi !== null): ?>
            <div class="alert alert-warning" role="alert">
                <?= htmlspecialchars($erroApi, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php
        renderVitrine(
            'NOVIDADES',
            'novidades',
            $novidades,
            $categoriasFiltro,
            'Nenhum produto ativo foi encontrado.'
        );
        ?>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="../assets/js/main.js"></script>

</body>

</html>
