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
    $resultado = [];
    foreach ($categorias as $categoria) {
        if (!empty($categoria['id_categoria_pai'])) continue;
        $resultado[] = ['id' => (int) $categoria['id_categoria'], 'label' => $categoria['nome']];
    }
    return $resultado;
}

function renderNavProdutos(
    array $categorias,
    ?int $categoriaSelecionada = null,
    string $titulo = 'PRODUTOS'
): void {

    $categoriasNav =
        obterCategoriasNavProdutos($categorias);
?>

    <div class="vitrine-largura d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

        <h2 class="fw-normal lh-1 mb-0" style="font-size:50px;">
            <?= htmlspecialchars(
                $titulo,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </h2>

        <nav
            class="d-flex align-items-center gap-2 filtros-produto"
            aria-label="Categorias de produtos">

            <a
                href="/pages/home.php"
                data-categoria-id="todos"
                class="btn btn-sm filtro-produto <?= $categoriaSelecionada === null
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
                    href="/pages/home.php?categorias=<?= (int) $categoria['id'] ?>"
                    data-categoria-id="<?= (int) $categoria['id'] ?>"
                    class="btn btn-sm filtro-produto <?= $categoriaAtiva
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

    </div>

<?php
}
