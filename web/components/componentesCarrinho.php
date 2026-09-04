<?php
/**
 * componentesCarrinho.php
 * ------------------------------------------------------------
 * Componente de FRONT-END responsável por renderizar um item
 * dentro do carrinho (imagem + nome + descrição + preços +
 * controles de quantidade + remover).
 *
 * IMPORTANTE:
 * - Este arquivo não busca nem recebe dados de back-end.
 * - A função abaixo tem valores padrão (placeholders) apenas
 *   para que o componente já "nasça" visualmente pronto,
 *   igual à imagem de referência.
 * - Quando o back-end estiver pronto, basta passar os
 *   parâmetros reais na chamada da função (em abaCarrinho.php
 *   ou em qualquer outro lugar que for usar o componente).
 * ------------------------------------------------------------
 */

// Evita que o <style> seja impresso mais de uma vez caso este
// arquivo seja incluído (require/include) mais de uma vez.
if (!defined('COMPONENTES_CARRINHO_STYLE_IMPRESSO')) {
    define('COMPONENTES_CARRINHO_STYLE_IMPRESSO', true);
    ?>
    <style>
        .item-carrinho {
            display: flex;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }

        .item-carrinho:last-child {
            border-bottom: none;
        }

        .item-carrinho .item-imagem {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
            background: #f2f2f2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-carrinho .item-imagem img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .item-carrinho .item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .item-carrinho .item-nome {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
            color: #222;
        }

        .item-carrinho .item-descricao {
            font-size: 12px;
            color: #777;
            margin: 0 0 4px 0;
        }

        .item-carrinho .item-precos {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .item-carrinho .preco-original {
            font-size: 12px;
            color: #aaa;
            text-decoration: line-through;
        }

        .item-carrinho .preco-desconto {
            font-size: 14px;
            font-weight: 700;
            color: #222;
        }

        .item-carrinho .item-quantidade {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .item-carrinho .btn-qtd {
            width: 22px;
            height: 22px;
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-carrinho .btn-qtd:hover {
            background: #f0f0f0;
        }

        .item-carrinho .qtd-numero {
            min-width: 16px;
            text-align: center;
            font-size: 14px;
        }

        .item-carrinho .btn-remover {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
            color: #444;
        }

        .item-carrinho .btn-remover:hover {
            color: #c0392b;
        }
    </style>
    <?php
}

/**
 * Renderiza (retorna em HTML) um item do carrinho.
 *
 * @param string $imagem         Caminho/URL da imagem do produto.
 * @param string $nome           Nome do produto.
 * @param string $descricao      Descrição curta do produto.
 * @param string $precoOriginal  Preço original (sem desconto), ex: "50,00".
 * @param string $precoDesconto  Preço com desconto aplicado, ex: "40,00".
 * @param int    $quantidade     Quantidade selecionada do item.
 * @param string $idItem         Identificador do item (usado nos botões, ex: data-id).
 *
 * @return string HTML do componente pronto para ser ecoado (echo).
 */
function renderItemCarrinho(
    string $imagem = 'https://via.placeholder.com/80',
    string $nome = 'Nome do Produto',
    string $descricao = 'Descrição do produto',
    string $precoOriginal = '0,00',
    string $precoDesconto = '0,00',
    int $quantidade = 1,
    string $idItem = ''
): string {
    ob_start();
    ?>
    <div class="item-carrinho" data-item-id="<?php echo htmlspecialchars($idItem); ?>">
        <div class="item-imagem">
            <img src="<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($nome); ?>">
        </div>
        <div class="item-info">
            <p class="item-nome"><?php echo htmlspecialchars($nome); ?></p>
            <p class="item-descricao"><?php echo htmlspecialchars($descricao); ?></p>

            <div class="item-precos">
                <?php if ($precoOriginal !== $precoDesconto): ?>
                    <span class="preco-original">R$ <?php echo htmlspecialchars($precoOriginal); ?></span>
                <?php endif; ?>
                <span class="preco-desconto">R$ <?php echo htmlspecialchars($precoDesconto); ?></span>
            </div>

            <div class="item-quantidade">
                <button type="button" class="btn-qtd btn-menos" data-item-id="<?php echo htmlspecialchars($idItem); ?>">-</button>
                <span class="qtd-numero"><?php echo (int) $quantidade; ?></span>
                <button type="button" class="btn-qtd btn-mais" data-item-id="<?php echo htmlspecialchars($idItem); ?>">+</button>
                <button type="button" class="btn-remover" data-item-id="<?php echo htmlspecialchars($idItem); ?>">🗑</button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}