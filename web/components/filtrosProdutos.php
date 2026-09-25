<?php

function renderFiltroCheckboxes(
    string $titulo,
    array $opcoes,
    string $name,
    string $id
): void {
    ?>

    <div class="bg-body-secondary rounded-1 p-2 mb-3">

        <button type="button"
            class="btn btn-sm w-100 d-flex justify-content-between align-items-center p-0 text-start border-0"
            data-bs-toggle="collapse" data-bs-target="#<?= $id ?>" aria-expanded="true" aria-controls="<?= $id ?>">
            <span class="fs-6">
                <?= htmlspecialchars($titulo) ?>
            </span>

            <span class="fs-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-caret-up-fill filtro-seta" viewBox="0 0 16 16">

                    <path
                        d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />

                </svg>
            </span>
        </button>

        <div id="<?= $id ?>" class="collapse show mt-2">

            <?php foreach ($opcoes as $opcao): ?>

                <?php
                $valor = htmlspecialchars($opcao);
                $inputId = $id . '-' . md5($opcao);
                ?>

                <div class="form-check fs-6 mb-1">

                    <input class="form-check-input" type="checkbox" name="<?= htmlspecialchars($name) ?>[]"
                        value="<?= $valor ?>" id="<?= $inputId ?>">

                    <label class="form-check-label" for="<?= $inputId ?>">
                        <?= $valor ?>
                    </label>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <?php
}


$filtros = $filtros ?? [];

$tipos = $filtros['tipos'] ?? [];
$cores = $filtros['cores'] ?? [];
$tamanhos = $filtros['tamanhos'] ?? [];
$categorias = $filtros['categorias'] ?? [];

$precoMin = (float) ($filtros['precoMin'] ?? 0);
$precoMax = (float) ($filtros['precoMax'] ?? 0);

?>

<aside class="filtros-produtos" style="width: 220px;">

    <?php if (!empty($tipos)): ?>

        <?php
        renderFiltroCheckboxes(
            'Produtos',
            $tipos,
            'tipo',
            'filtroProdutos'
        );
        ?>

    <?php endif; ?>

    <?php if (!empty($cores)): ?>

        <?php
        renderFiltroCheckboxes(
            'Cores',
            $cores,
            'cor',
            'filtroCores'
        );
        ?>

    <?php endif; ?>

    <?php if (!empty($tamanhos)): ?>

        <?php
        renderFiltroCheckboxes(
            'Tamanhos',
            $tamanhos,
            'tamanho',
            'filtroTamanhos'
        );
        ?>

    <?php endif; ?>

    <!-- PREÇO -->

    <?php if ($precoMax > $precoMin): ?>

        <div class="bg-body-secondary rounded-1 p-2 mb-3">

            <button type="button"
                class="btn btn-sm w-100 d-flex justify-content-between align-items-center p-0 text-start border-0"
                data-bs-toggle="collapse" data-bs-target="#filtroPreco" aria-expanded="true" aria-controls="filtroPreco">

                <span class="fs-6">
                    Preço
                </span>

                <span class="fs-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-caret-up-fill filtro-seta" viewBox="0 0 16 16">

                        <path
                            d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />

                    </svg>
                </span>

            </button>

            <div id="filtroPreco" class="collapse show mt-2">

                <div class="px-1">

                    <div class="slider-preco">

                        <div class="slider-preco-trilho"></div>

                        <div id="faixaPreco"></div>

                        <input type="range" id="precoMin" name="precoMin" min="<?= $precoMin ?>" max="<?= $precoMax ?>"
                            value="<?= $precoMin ?>" step="1">

                        <input type="range" id="precoMax" name="precoMax" min="<?= $precoMin ?>" max="<?= $precoMax ?>"
                            value="<?= $precoMax ?>" step="1">

                    </div>

                    <div class="d-flex justify-content-between fs-6 mt-2">

                        <span>
                            R$
                            <span id="valorPrecoMin">
                                <?= number_format($precoMin, 2, ',', '.') ?>
                            </span>
                        </span>

                        <span>
                            R$
                            <span id="valorPrecoMax">
                                <?= number_format($precoMax, 2, ',', '.') ?>
                            </span>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <?php if (!empty($categorias)): ?>

        <?php
        renderFiltroCheckboxes(
            'Categorias',
            $categorias,
            'categoria',
            'filtroCategorias'
        );
        ?>

    <?php endif; ?>

</aside>