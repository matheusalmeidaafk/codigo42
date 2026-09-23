<?php

require_once __DIR__ . '/cardProduto.php';

function renderCarrosselProdutos(array $produtos): void
{
    if (empty($produtos)) {
        return;
    }

    $gruposProdutos = array_chunk($produtos, 5);
?>

    <section class="container-fluid py-4">

        <div
            id="carrosselProdutos"
            class="carousel slide">

            <div class="row g-0 align-items-stretch">

                <div
                    class="col-auto d-flex align-items-center justify-content-center bg-white px-2">

                    <button
                        class="btn border-0 p-0"
                        type="button"
                        data-bs-target="#carrosselProdutos"
                        data-bs-slide="prev">

                        <img
                            src="../assets/images/banner/arrow.png"
                            class="seta"
                            alt="Anterior">

                        <span class="visually-hidden">
                            Anterior
                        </span>

                    </button>

                </div>

                <div class="col">

                    <div class="carousel-inner">

                        <?php foreach ($gruposProdutos as $indice => $grupo): ?>

                            <div class="carousel-item <?= $indice === 0 ? 'active' : '' ?>">

                                <div class="d-flex flex-wrap justify-content-center">

                                    <?php foreach ($grupo as $produto): ?>

                                        <div class="d-flex justify-content-center">

                                            <?php
                                            CardProduto(
                                                $produto['nome'],
                                                (int) $produto['estrelas'],
                                                (float) $produto['preco'],
                                                $produto['imagem_url'] ?? 'https://placehold.co/600x600?text=Sem+Imagem'
                                            );
                                            ?>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

                <div
                    class="col-auto d-flex align-items-center justify-content-center bg-white px-2">

                    <button
                        class="btn border-0 p-0"
                        type="button"
                        data-bs-target="#carrosselProdutos"
                        data-bs-slide="next">

                        <img
                            src="../assets/images/banner/arrow.png"
                            class="seta proximo"
                            alt="Próximo">

                        <span class="visually-hidden">
                            Próximo
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </section>

<?php
}
