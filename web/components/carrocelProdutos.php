<?php

require_once __DIR__ . '/cardTeste.php';

function renderCarrocelProdutos()
{
    $produtosTeste = [
        'Produto 1',
        'Produto 2',
        'Produto 3',
        'Produto 4',
        'Produto 5',
        'Produto 6',
        'Produto 7',
        'Produto 8',
        'Produto 9',
        'Produto 10'
    ];

    $gruposProdutos = array_chunk($produtosTeste, 5);
?>

    <section class="container py-4">

        <div
            id="carrocelProdutos"
            class="carousel slide"
        >

            <div class="row g-0 align-items-stretch">

                <div class="col-auto d-flex align-items-center justify-content-center bg-white px-2">

                    <button
                        class="btn border-0 fs-2 text-body"
                        type="button"
                        data-bs-target="#carrocelProdutos"
                        data-bs-slide="prev"
                        aria-label="Produto anterior"
                    >
                        X
                    </button>

                </div>

                <div class="col">

                    <div class="carousel-inner">

                        <?php foreach ($gruposProdutos as $indice => $grupo): ?>

                            <div class="carousel-item <?= $indice === 0 ? 'active' : '' ?>">

                                <div class="row row-cols-5 g-2">

                                    <?php foreach ($grupo as $produto): ?>

                                        <div class="col">
                                            <?php renderCardTeste($produto); ?>
                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

                <div class="col-auto d-flex align-items-center justify-content-center bg-white px-2">

                    <button
                        class="btn border-0 fs-2 text-body"
                        type="button"
                        data-bs-target="#carrocelProdutos"
                        data-bs-slide="next"
                        aria-label="Próximo produto"
                    >
                        X
                    </button>

                </div>

            </div>

        </div>

    </section>

<?php
}