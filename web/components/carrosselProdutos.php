<?php

require_once __DIR__ . '/cardTeste.php';

function renderCarrosselProdutos()
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
            id="carrosselProdutos"
            class="carousel slide">

            <div class="row g-0 align-items-stretch">

                <div class="col-auto d-flex align-items-center justify-content-center bg-white px-2">

                    <button
                        class="btn border-0 p-0"
                        type="button"
                        data-bs-target="#carrosselProdutos"
                        data-bs-slide="prev">
                        <img
                            src="./assets/images/banner/arrow.png"
                            class="seta"
                            alt="Previous">

                        <span class="visually-hidden">
                            Previous
                        </span>
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
                        class="btn border-0 p-0"
                        type="button"
                        data-bs-target="#carrosselProdutos"
                        data-bs-slide="next">
                        <img
                            src="./assets/images/banner/arrow.png"
                            class="seta proximo"
                            alt="Next">

                        <span class="visually-hidden">
                            Next
                        </span>
                    </button>

                </div>

            </div>

        </div>

    </section>

<?php
}
