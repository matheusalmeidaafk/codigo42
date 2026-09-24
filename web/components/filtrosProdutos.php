<?php

function renderFiltroCheckboxes(
    string $titulo,
    array $opcoes,
    string $name,
    string $id
): void {
?>

    <div class="bg-body-secondary rounded-1 p-2 mb-3">

        <button
            type="button"
            class="btn btn-sm w-100 d-flex justify-content-between align-items-center p-0 text-start border-0"
            data-bs-toggle="collapse"
            data-bs-target="#<?= $id ?>"
            aria-expanded="true"
            aria-controls="<?= $id ?>"
        >
            <span class="small">
                <?= htmlspecialchars($titulo) ?>
            </span>

            <span class="small">
                ▲
            </span>
        </button>

        <div
            id="<?= $id ?>"
            class="collapse show mt-2"
        >

            <?php foreach ($opcoes as $opcao): ?>

                <?php
                $valor = htmlspecialchars($opcao);
                $inputId = $id . '-' . md5($opcao);
                ?>

                <div class="form-check small mb-1">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="<?= htmlspecialchars($name) ?>[]"
                        value="<?= $valor ?>"
                        id="<?= $inputId ?>"
                    >

                    <label
                        class="form-check-label"
                        for="<?= $inputId ?>"
                    >
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

<aside class="filtros-produtos">

    <!-- PRODUTOS -->
    <!-- Categorias pai -->

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


    <!-- CORES -->

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


    <!-- TAMANHOS -->

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

            <button
                type="button"
                class="btn btn-sm w-100 d-flex justify-content-between align-items-center p-0 text-start border-0"
                data-bs-toggle="collapse"
                data-bs-target="#filtroPreco"
                aria-expanded="true"
                aria-controls="filtroPreco"
            >
                <span class="small">
                    Preço
                </span>

                <span class="small">
                    ▲
                </span>
            </button>

            <div
                id="filtroPreco"
                class="collapse show mt-2"
            >

                <div class="px-1">

                    <input
                        type="range"
                        class="form-range"
                        id="precoMin"
                        name="precoMin"
                        min="<?= $precoMin ?>"
                        max="<?= $precoMax ?>"
                        value="<?= $precoMin ?>"
                        step="1"
                    >

                    <input
                        type="range"
                        class="form-range"
                        id="precoMax"
                        name="precoMax"
                        min="<?= $precoMin ?>"
                        max="<?= $precoMax ?>"
                        value="<?= $precoMax ?>"
                        step="1"
                    >

                    <div class="d-flex justify-content-between small">

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