<?php

function CardProduto(array $produto): void
{
    $nome = htmlspecialchars($produto['nome'] ?? 'Produto');
    $descricao = htmlspecialchars($produto['descricao'] ?? '');
    $preco = number_format((float) ($produto['preco'] ?? 0), 2, ',', '.');
    $imagem = htmlspecialchars($produto['imagem'] ?? '');
    ?>

    <article class="card-produto h-100 border border-dark overflow-hidden">

        <div class="card-produto-imagem">

            <img
                src="<?= $imagem ?>"
                alt="<?= $nome ?>"
                class="card-produto-img"
            >

        </div>

        <div class="card-produto-info">

            <div class="card-produto-nome">
                <?= $nome ?>
            </div>

            <?php if ($descricao): ?>
                <div class="card-produto-descricao">
                    <?= $descricao ?>
                </div>
            <?php endif; ?>

            <div class="card-produto-preco">
                <span>Por:</span>
                R$ <?= $preco ?>
            </div>

        </div>

        <div class="card-produto-hover">

            <button
                type="button"
                class="btn btn-success card-produto-carrinho"
                data-id="<?= htmlspecialchars($produto['id'] ?? '') ?>"
            >
                Adicionar ao carrinho
            </button>

        </div>

    </article>

    <?php
}