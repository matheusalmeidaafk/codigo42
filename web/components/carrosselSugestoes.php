
<?php

require_once __DIR__ . '/cardProduto.php';

function renderCarrosselSugestoes(array $produtos): void
{
    if (empty($produtos)) {
        return;
    }

    $cardsPorSlide = 5;
    $gruposProdutos = array_chunk($produtos, $cardsPorSlide);
    ?>

    <section class="sugestoes-produtos">

        <h2 class="titulo-sugestoes">
            VOCÊ TAMBÉM PODE GOSTAR
        </h2>

        <div id="carrosselSugestoes" class="carousel slide">

            <div class="carrossel-sugestoes-area">

                <button
                    class="btn border-0 p-0 carrossel-sugestoes-seta"
                    type="button"
                    data-bs-target="#carrosselSugestoes"
                    data-bs-slide="prev"
                    aria-label="Produtos anteriores">

                    <img
                        src="/assets/images/banner/arrow.png"
                        class="seta-sugestoes"
                        alt="Anterior">

                </button>

                <div class="carousel-inner">

                    <?php foreach ($gruposProdutos as $indice => $grupo): ?>

                        <div class="carousel-item <?= $indice === 0 ? 'active' : '' ?>">

                            <div class="lista-produtos-sugestoes">

                                <?php foreach ($grupo as $produto): ?>

                                    <div class="produto-sugestao-item">

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
                                            $produto['variacao_imagem_url']
                                                ?? 'https://placehold.co/600x600?text=Sem+Imagem',
                                            $produto['tamanhos'] ?? []
                                        );
                                        ?>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <button
                    class="btn border-0 p-0 carrossel-sugestoes-seta"
                    type="button"
                    data-bs-target="#carrosselSugestoes"
                    data-bs-slide="next"
                    aria-label="Próximos produtos">

                    <img
                        src="/assets/images/banner/arrow.png"
                        class="seta-sugestoes proxima-sugestao"
                        alt="Próximo">

                </button>

            </div>

        </div>

    </section>

    <?php
}