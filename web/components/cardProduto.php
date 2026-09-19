<?php

function CardProduto(
    string $titulo,
    int $estrelas,
    float $preco,
    string $imagem
): void {

    $estrelas = max(1, min(5, $estrelas));
    ?>

    <article class="card border border-dark rounded-0 overflow-hidden h-100">

        <div class="ratio ratio-2x3">
            <img src="<?= htmlspecialchars($imagem) ?>" alt="<?= htmlspecialchars($titulo) ?>"
                class="w-100 h-100 object-fit-cover">
        </div>

        <div class="bg-dark text-white px-2 py-1">

            <div class="d-flex justify-content-between align-items-center gap-2">

                <p class="mb-0 lh-sm">
                    <?= htmlspecialchars($titulo) ?>
                </p>

                <div class="d-flex align-items-center gap-2 text-nowrap">

                    <span class="text-secondary">
                        De
                    </span>

                    <span class="text-secondary text-decoration-line-through">
                        R$ 200,00
                    </span>

                    <span>
                        Por
                    </span>

                    <span class="bg-info text-dark px-2 py-1">
                        R$ <?= number_format($preco, 2, ',', '.') ?>
                    </span>

                </div>

            </div>

            <div class="d-flex gap-1 mt-1">

                <?php for ($i = 1; $i <= 5; $i++): ?>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="<?= $i <= $estrelas
                        ? 'estrela-preenchida'
                        : 'estrela-vazia' ?>">
                        <path
                            d="M12 2.5L14.9 8.4L21.5 9.3L16.7 13.9L17.8 20.5L12 17.4L6.2 20.5L7.3 13.9L2.5 9.3L9.1 8.4L12 2.5Z" />
                    </svg>

                <?php endfor; ?>

            </div>

        </div>

    </article>

    <?php
}