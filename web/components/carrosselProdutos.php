<?php

require_once __DIR__ . '/cardProduto.php';

function renderCarrosselProdutos(array $produtos): void
{
    if (empty($produtos)) {
        return;
    }

    $cardsPorSlide = 5;

    $gruposProdutos = array_chunk(
        $produtos,
        $cardsPorSlide
    );
?>

    <div class="py-2">

        <div
            id="carrosselProdutos"
            class="carousel slide">

            <div class="carrossel-area">

                <button
                    class="btn border-0 p-0 carrossel-seta"
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

                            <div class="d-flex flex-nowrap gap-1">

                                <?php foreach ($grupo as $produto): ?>

                                    <div class="produto-carrossel-item flex-shrink-0">

                                        <?php
                                        CardProduto(
                                            $produto['nome'],
                                            (int) ($produto['estrelas'] ?? 0),
                                            (float) ($produto['preco'] ?? 0),
                                            (float) (
                                                $produto['preco_final']
                                                ?? $produto['preco']
                                                ?? 0
                                            ),
                                            isset($produto['porcentagem_desconto'])
                                                ? (float) $produto['porcentagem_desconto']
                                                : null,
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
                    class="btn border-0 p-0 carrossel-seta"
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