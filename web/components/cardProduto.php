<?php

function CardProduto(
    string $titulo,
    int $estrelas,
    float $precoOriginal,
    float $precoFinal,
    ?float $porcentagemDesconto,
    string $imagem
): void {

    $estrelas = max(0, min(5, $estrelas));

    $temDesconto = $porcentagemDesconto !== null
        && $porcentagemDesconto > 0;
?>

    <article class="card border-0 rounded-0 overflow-hidden w-100">

        <div class="produto-card-imagem w-100 border border-dark border-2">

            <img
                src="<?= htmlspecialchars($imagem) ?>"
                alt="<?= htmlspecialchars($titulo) ?>"
                class="w-100 h-100 object-fit-cover">

        </div>

        <div class="row g-0 bg-dark text-white">

            <div class="col-6 p-2">

                <div
                    class="lh-sm ps-1 text-center"
                    style="height: 40px;">

                    <?= htmlspecialchars($titulo) ?>

                </div>

                <div class="d-flex justify-content-center mt-1">

                    <?php for ($i = 1; $i <= 5; $i++): ?>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="15"
                            height="15"
                            viewBox="0 0 24 24"
                            fill="<?= $i <= $estrelas
                                        ? 'var(--bs-teal)'
                                        : 'var(--bs-secondary)' ?>">

                            <path
                                d="M12 2.5L14.9 8.4L21.5 9.3L16.7 13.9L17.8 20.5L12 17.4L6.2 20.5L7.3 13.9L2.5 9.3L9.1 8.4L12 2.5Z" />

                        </svg>

                    <?php endfor; ?>

                </div>

            </div>

            <div class="col-6 d-flex flex-column">

                <?php if ($temDesconto): ?>

                    <div class="produto-preco-linha d-flex align-items-center justify-content-between px-2 py-1 text-nowrap mt-auto">

                        <span class="text-secondary">
                            De:
                        </span>

                        <span class="text-secondary text-decoration-line-through">
                            R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                        </span>

                    </div>

                    <div class="produto-preco-linha produto-preco-normal d-flex align-items-center justify-content-between text-dark px-2 py-1 text-nowrap">

                        <span>
                            Por:
                        </span>

                        <span>
                            R$ <?= number_format($precoFinal, 2, ',', '.') ?>
                        </span>

                    </div>

                <?php else: ?>

                    <div class="produto-preco-linha produto-preco-normal d-flex align-items-center justify-content-between text-dark px-2 py-1 text-nowrap mt-auto">

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

<?php
}
