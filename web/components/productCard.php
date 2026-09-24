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

    $imagemProduto = htmlspecialchars(
        $produto['imagem_url']
            ?? $produto['imagem']
            ?? 'https://placehold.co/300x300?text=Produto',
        ENT_QUOTES,
        'UTF-8'
    );

    $categoriaIds = [];

    foreach (($produto['categorias'] ?? []) as $categoria) {
        $categoriaId = (int) ($categoria['id_categoria'] ?? 0);

        if ($categoriaId > 0) {
            $categoriaIds[] = $categoriaId;
        }
    }

    $categoriaIds = array_values(array_unique($categoriaIds));

    $categoriasProduto = htmlspecialchars(
        implode(',', $categoriaIds),
        ENT_QUOTES,
        'UTF-8'
    );
?>

    <div
        class="produto-item"
        data-categorias="<?= $categoriasProduto ?>">

        <article class="card produto-card h-100 rounded-0">

            <div class="produto-imagem-container">
                <img
                    src="<?= $imagemProduto ?>"
                    class="card-img-top produto-imagem rounded-0"
                    alt="<?= $nomeProduto ?>"
                    loading="lazy">
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
