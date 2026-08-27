<?php

function renderProductCard($produto)
{
    $nomeProduto = $produto['nome'];
    $descricaoProduto = $produto['descricao'];
    $precoProduto = $produto['preco'];
    $imagemProduto = $produto['imagem'];
    $estoqueProduto = $produto['estoque'];

    $produtoDisponivel = $estoqueProduto > 0;
?>

    <div class="col">
        <div class="card h-100 shadow-sm">

            <img
                src="<?= $imagemProduto ?>"
                class="card-img-top"
                alt="<?= $nomeProduto ?>">

            <div class="card-body d-flex flex-column">

                <h5 class="card-title">
                    <?= $nomeProduto ?>
                </h5>

                <p class="card-text">
                    <?= $descricaoProduto ?>
                </p>

                <p class="fw-bold fs-5">
                    R$ <?= number_format($precoProduto, 2, ',', '.') ?>
                </p>

                <?php if ($produtoDisponivel): ?>

                    <p class="text-success">
                        <?= $estoqueProduto ?> unidades disponíveis
                    </p>

                    <button class="btn btn-primary mt-auto">
                        Adicionar ao carrinho
                    </button>

                <?php else: ?>

                    <p class="text-danger">
                        Produto indisponível
                    </p>

                    <button
                        class="btn btn-secondary mt-auto"
                        disabled>
                        Indisponível
                    </button>

                <?php endif; ?>

            </div>

        </div>
    </div>

<?php
}
