<?php

function renderProductCard(array $produto): void
{
    $nomeProduto = htmlspecialchars($produto['nome']);
    $descricaoProduto = htmlspecialchars($produto['descricao']);
    $precoProduto = number_format((float) $produto['preco'], 2, ',', '.');
    $imagemProduto = htmlspecialchars($produto['imagem_url']);
    $categoriaProduto = htmlspecialchars($produto['categoria']);
?>

    <div class="produto-mini-card produtoItem" data-categoria="<?= $categoriaProduto ?>">
        <div class="produto-thumb">
            <img src="<?= $imagemProduto ?>" alt="<?= $nomeProduto ?>">
        </div>

        <div class="produto-info">
            <div class="produto-nome"><?= $nomeProduto ?></div>
            <div class="produto-descricao"><?= $descricaoProduto ?></div>
            <div class="produto-preco">Por R$ <?= $precoProduto ?></div>
        </div>
    </div>

<?php
}

function renderProductSection(string $titulo, string $secaoId, array $produtos): void
{
?>
    <section class="vitrine-section mb-5">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <h2 class="vitrine-titulo mb-0"><?= $titulo ?></h2>

            <div class="d-flex gap-1 flex-wrap">
                <button type="button" class="filtroProduto ativo" data-secao="<?= $secaoId ?>" data-categoria="todos">TUDO</button>
                <button type="button" class="filtroProduto" data-secao="<?= $secaoId ?>" data-categoria="camiseta">CAMISETAS</button>
                <button type="button" class="filtroProduto" data-secao="<?= $secaoId ?>" data-categoria="caneca">CANECAS</button>
                <button type="button" class="filtroProduto" data-secao="<?= $secaoId ?>" data-categoria="adesivo">ADESIVOS</button>
            </div>
        </div>

        <div class="vitrine-box">
            <button class="vitrine-arrow vitrine-arrow-left" type="button" aria-label="Anterior">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="vitrine-lista" id="<?= $secaoId ?>">
                <?php foreach ($produtos as $produto): ?>
                    <?php renderProductCard($produto); ?>
                <?php endforeach; ?>
            </div>

            <button class="vitrine-arrow vitrine-arrow-right" type="button" aria-label="Próximo">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

    </section>
<?php
}
