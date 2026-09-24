<?php

function renderProductCard(array $produto): void
{
    $nomeProduto = htmlspecialchars(
        $produto['nome'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );

    $descricaoProduto = htmlspecialchars(
        $produto['descricao'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );

    $precoProduto = (float) ($produto['preco'] ?? 0);

    // Aceita o padrão futuro do banco e também o mock antigo.
    $imagemProduto = htmlspecialchars(
        $produto['imagem_url']
            ?? $produto['imagem']
            ?? 'https://placehold.co/300x300?text=Produto',
        ENT_QUOTES,
        'UTF-8'
    );

    $categoriaProduto = htmlspecialchars(
        strtolower($produto['categoria'] ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
?>

    <div
        class="produto-item"
        data-categoria="<?= $categoriaProduto ?>">

        <article class="card produto-card h-100 rounded-0">

            <div class="produto-imagem-container">

                <img
                    src="<?= $imagemProduto ?>"
                    class="card-img-top produto-imagem rounded-0"
                    alt="<?= $nomeProduto ?>">

            </div>

            <div class="card-body p-2 d-flex flex-column">

                <h3 class="card-title produto-nome mb-1">
                    <?= $nomeProduto ?>
                </h3>

                <p class="card-text produto-descricao text-secondary mb-1">
                    <?= $descricaoProduto ?>
                </p>

                <div class="mt-auto">

                    <p class="produto-preco mb-1">
                        R$ <?= number_format(
                                $precoProduto,
                                2,
                                ',',
                                '.'
                            ) ?>
                    </p>

                    <button
                        type="button"
                        class="btn btn-success btn-sm rounded-0 w-100 produto-comprar">
                        Adicionar ao carrinho
                    </button>

                </div>

            </div>

        </article>

    </div>

<?php
}
