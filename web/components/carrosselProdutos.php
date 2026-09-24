<?php

require_once __DIR__ . '/cardProduto.php';

function renderCarrosselProdutos(array $produtos): void
{
    if (empty($produtos)) {
        return;
    }

    $cardsPorSlide = 5;
    $larguraCard = 260;
    $gapEntreCards = 4;

    $larguraCarrossel =
        ($cardsPorSlide * $larguraCard)
        + (($cardsPorSlide - 1) * $gapEntreCards);

    $gruposProdutos =
        array_chunk(
            $produtos,
            $cardsPorSlide
        );
?>

    <div class="py-2">

        <div
            id="carrosselProdutos"
            class="carousel slide">

            <div
                class="position-relative mx-auto"
                style="width: <?= $larguraCarrossel ?>px; max-width: 100%;">

                <button
                    class="btn border-0 p-0 position-absolute top-50 translate-middle-y z-3"
                    style="left: -60px;"
                    type="button"
                    data-bs-target="#carrosselProdutos"
                    data-bs-slide="prev">

                    <img
                        src="/assets/images/banner/arrow.png"
                        class="seta"
                        alt="Anterior">

                    <span class="visually-hidden">
                        Anterior
                    </span>

                </button>

                <div class="carousel-inner overflow-hidden">

                    <?php foreach ($gruposProdutos as $indice => $grupo): ?>

                        <div
                            class="carousel-item <?= $indice === 0 ? 'active' : '' ?>">

                            <div
                                class="d-flex flex-nowrap justify-content-start gap-1">

                                <?php foreach ($grupo as $produto): ?>

                                    <div class="flex-shrink-0">

                                        <?php
                                        CardProduto(
                                            $produto['nome'],
                                            (int) $produto['estrelas'],
                                            (float) $produto['preco'],
                                            $produto['imagem_url']
                                                ?? 'https://placehold.co/600x600?text=Sem+Imagem'
                                        );
                                        ?>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <button
                    class="btn border-0 p-0 position-absolute top-50 translate-middle-y z-3"
                    style="right: -60px;"
                    type="button"
                    data-bs-target="#carrosselProdutos"
                    data-bs-slide="next">

                    <img
                        src="/assets/images/banner/arrow.png"
                        class="seta proximo"
                        alt="Próximo">

                    <span class="visually-hidden">
                        Próximo
                    </span>

                </button>

            </div>

        </div>

    </div>

<?php
}
