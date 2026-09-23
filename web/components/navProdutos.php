<?php

function normalizarCategoriaProduto(string $texto): string
{
    $texto = trim($texto);

    $semAcento = iconv(
        'UTF-8',
        'ASCII//TRANSLIT//IGNORE',
        $texto
    );

    return strtolower(
        $semAcento !== false
            ? $semAcento
            : $texto
    );
}

function obterCategoriasNavProdutos(array $categorias): array
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

        $nomeCategoria =
            (string) ($categoria['nome'] ?? '');

        $nomeNormalizado =
            normalizarCategoriaProduto($nomeCategoria);

        if (
            $idCategoria <= 0
            || !isset($ordemDesejada[$nomeNormalizado])
        ) {
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

function renderNavProdutos(
    array $categorias,
    ?int $categoriaSelecionada = null
): void {

    $categoriasNav =
        obterCategoriasNavProdutos($categorias);
?>

    <nav
        class="btn-group btn-group-sm filtros-produto"
        aria-label="Categorias de produtos">

        <a
            href="/"
            class="btn <?= $categoriaSelecionada === null
                            ? 'btn-dark'
                            : 'btn-outline-dark' ?>">
            TUDO
        </a>

        <?php foreach ($categoriasNav as $categoria): ?>

            <?php
            $categoriaAtiva =
                $categoriaSelecionada === (int) $categoria['id'];
            ?>

            <a
                href="/?categoriaId=<?= (int) $categoria['id'] ?>"
                class="btn <?= $categoriaAtiva
                                ? 'btn-dark'
                                : 'btn-outline-dark' ?>">

                <?= htmlspecialchars(
                    $categoria['label'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </a>

        <?php endforeach; ?>

    </nav>

<?php
}
