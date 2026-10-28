<?php

function CardProduto(
    int $idProduto,
    string $titulo,
    float $estrelas,
    float $precoOriginal,
    float $precoFinal,
    ?float $porcentagemDesconto,
    string $imagem,
    array $tamanhos = []
): void {

    $estrelas = max(0, min(5, $estrelas));
    

    $temDesconto = $porcentagemDesconto !== null
        && $porcentagemDesconto > 0;
    ?>
    <div class="produto-card-wrapper">

        <article class="card border-0 rounded-0 overflow-hidden w-100">

            <div
                class=" hover-card d-flex flex-column justify-content-end align-items-center position-absolute bottom-0 start-0 w-100 border border-dark border-2 p-2 opacity-0 invisible z-3">

                <?php if ($tamanhos): ?><h2>Tamanho:</h2><?php endif; ?>
                <div class="d-flex justify-content-center gap-2 mb-2">
                    <?php if (!empty($tamanhos)): ?>
                        <div class="d-flex gap-2 flex-wrap justify-content-center">
                            <?php foreach ($tamanhos as $tamanho): ?>
                                <button type="button" class="btn btn-sm bg-white border rounded-2 px-3 py-1 tamanho-btn"
                                    data-tamanho="<?= htmlspecialchars($tamanho) ?>">
                                    <?= htmlspecialchars($tamanho) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>

                <button type="button" class="btn btn-success w-75 js-add-cart"
                    data-id="<?= $idProduto ?>"
                    data-nome="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>"
                    data-preco="<?= htmlspecialchars((string) $precoFinal, ENT_QUOTES, 'UTF-8') ?>"
                    data-imagem="<?= htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8') ?>"
                    data-requer-tamanho="<?= !empty($tamanhos) ? '1' : '0' ?>">
                    Escolher opções
                </button>

            </div>


            <a href="/pages/produto.php?id=<?= $idProduto ?>" class="produto-card-imagem d-block w-100 border border-dark border-2" aria-label="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>">

                <img src="<?= htmlspecialchars($imagem) ?>" alt="<?= htmlspecialchars($titulo) ?>"
                    class="w-100 h-100 object-fit-cover">

            </a>

            <div class="row g-0 bg-dark text-white">

                <div class="col-6 p-2">

                    <div class="lh-sm ps-1 text-center" style="height: 40px;">

                        <a class="text-reset text-decoration-none" href="/pages/produto.php?id=<?= $idProduto ?>"><?= htmlspecialchars($titulo) ?></a>

                    </div>

                    <div class="card-avaliacao" role="img" aria-label="<?= $estrelas > 0 ? number_format($estrelas, 1, ',', '.') . ' de 5 estrelas' : 'Sem avaliações' ?>" title="<?= $estrelas > 0 ? number_format($estrelas, 1, ',', '.') . ' de 5 estrelas' : 'Sem avaliações' ?>">
                        <span class="estrelas-base" aria-hidden="true">★★★★★<span class="estrelas-preenchidas" style="width: <?= $estrelas * 20 ?>%">★★★★★</span></span>
                    </div>

                </div>

                <div class="col-6 d-flex flex-column">

                    <?php if ($temDesconto): ?>

                        <div
                            class="produto-preco-linha d-flex align-items-center justify-content-between px-2 py-1 text-nowrap mt-auto">

                            <span class="text-secondary">
                                De:
                            </span>

                            <span class="text-secondary text-decoration-line-through">
                                R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                            </span>

                        </div>

                        <div
                            class="produto-preco-linha produto-preco-normal d-flex align-items-center justify-content-between text-dark px-2 py-1 text-nowrap">

                            <span>
                                Por:
                            </span>

                            <span>
                                R$ <?= number_format($precoFinal, 2, ',', '.') ?>
                            </span>

                        </div>

                    <?php else: ?>

                        <div
                            class="produto-preco-linha produto-preco-normal d-flex align-items-center justify-content-between text-dark px-2 py-1 text-nowrap mt-auto">

                            <span>
                                Preço:
                            </span>

                            <span>
                                R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                            </span>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </article>
    </div>

    <?php
}
