<?php
/**
 * abaCarrinho.php
 * ------------------------------------------------------------
 * Componente de FRONT-END responsável pela aba/painel do
 * carrinho (área branca lateral), igual à imagem de referência.
 *
 * IMPORTANTE:
 * - Este arquivo NÃO mexe em back-end. Os valores abaixo
 *   ($itensDoCarrinho, totais, etc.) são apenas placeholders
 *   estáticos para montar o front-end.
 * - Quando o back-end estiver pronto, basta substituir o array
 *   $itensDoCarrinho e as variáveis de totais pelos dados reais.
 * - O painel foi feito para comportar MAIS itens do que os dois
 *   mostrados na imagem de exemplo — por isso a lista de itens
 *   tem rolagem (scroll) própria, sem quebrar o layout do
 *   cabeçalho e do rodapé de totais.
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/componentesCarrinho.php';

// ------------------------------------------------------------
// DADOS DE EXEMPLO (placeholder) — viriam do back-end no futuro
// ------------------------------------------------------------
$itensDoCarrinho = [
    [
        'id'             => '1',
        'imagem'         => 'https://via.placeholder.com/80?text=Caneca',
        'nome'           => 'Caneca chococat',
        'descricao'      => 'Caneca Ceramica Hello Kitty Azul',
        'precoOriginal'  => '50,00',
        'precoDesconto'  => '40,00',
        'quantidade'     => 2,
    ],
    [
        'id'             => '2',
        'imagem'         => 'https://via.placeholder.com/80?text=Camiseta',
        'nome'           => 'Camiseta Samurai',
        'descricao'      => 'Camiseta Samurai Cyberpunk Preta',
        'precoOriginal'  => '200,00',
        'precoDesconto'  => '150,00',
        'quantidade'     => 1,
    ],
    // Mais itens podem ser adicionados aqui — a lista tem rolagem
    // própria para não quebrar o layout do cabeçalho/rodapé.
];

$totalItens = count($itensDoCarrinho);
$totalDosItens = '300,00'; // placeholder
$descontos     = '70,00';  // placeholder
$subtotal      = '230,00'; // placeholder
?>
<style>
    .carrinho-painel {
        width: 320px;
        max-width: 100%;
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        font-family: Arial, Helvetica, sans-serif;
        color: #222;
    }

    .carrinho-painel .carrinho-cabecalho {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-bottom: 1px solid #eee;
    }

    .carrinho-painel .carrinho-cabecalho h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
    }

    .carrinho-painel .btn-fechar-carrinho {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: none;
        background: #e6e6e6;
        color: #333;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .carrinho-painel .btn-fechar-carrinho:hover {
        background: #d8d8d8;
    }

    .carrinho-painel .carrinho-lista-itens {
        padding: 4px 18px;
        max-height: 360px;
        overflow-y: auto;
        flex: 1;
    }

    .carrinho-painel .carrinho-lista-itens:empty::after {
        content: "Seu carrinho está vazio.";
        display: block;
        text-align: center;
        color: #999;
        font-size: 13px;
        padding: 24px 0;
    }

    .carrinho-painel .carrinho-rodape {
        padding: 16px 18px 18px 18px;
        border-top: 1px solid #eee;
    }

    .carrinho-painel .linha-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .carrinho-painel .linha-total .rotulo {
        font-weight: 700;
    }

    .carrinho-painel .linha-total.desconto .valor {
        color: #d9534f;
    }

    .carrinho-painel .linha-total.subtotal {
        margin-top: 8px;
        font-size: 15px;
    }

    .carrinho-painel .linha-total.subtotal .rotulo,
    .carrinho-painel .linha-total.subtotal .valor {
        color: #2e8b57;
        font-weight: 700;
    }

    .carrinho-painel .btn-finalizar-pedido {
        width: 100%;
        margin-top: 14px;
        padding: 12px 0;
        border: none;
        border-radius: 6px;
        background: #2e8b57;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
    }

    .carrinho-painel .btn-finalizar-pedido:hover {
        background: #276d46;
    }

    .carrinho-painel .link-saiba-mais {
        display: block;
        margin-top: 10px;
        text-align: center;
        font-size: 12px;
    }

    .carrinho-painel .link-saiba-mais a {
        color: #2b6cb0;
        text-decoration: underline;
    }
</style>

<div class="carrinho-painel">

    <!-- Cabeçalho -->
    <div class="carrinho-cabecalho">
        <h3>Carrinho (<?php echo (int) $totalItens; ?> <?php echo $totalItens === 1 ? 'item' : 'itens'; ?>)</h3>
        <button type="button" class="btn-fechar-carrinho" id="btnFecharCarrinho">✕</button>
    </div>

    <!-- Lista de itens (usa o componente de componentesCarrinho.php) -->
    <div class="carrinho-lista-itens" id="listaItensCarrinho">
        <?php foreach ($itensDoCarrinho as $item): ?>
            <?php
            echo renderItemCarrinho(
                $item['imagem'],
                $item['nome'],
                $item['descricao'],
                $item['precoOriginal'],
                $item['precoDesconto'],
                $item['quantidade'],
                $item['id']
            );
            ?>
        <?php endforeach; ?>
    </div>

    <!-- Totais / rodapé -->
    <div class="carrinho-rodape">
        <div class="linha-total">
            <span class="rotulo">Total dos itens</span>
            <span class="valor">R$ <?php echo htmlspecialchars($totalDosItens); ?></span>
        </div>
        <div class="linha-total desconto">
            <span class="rotulo">Descontos</span>
            <span class="valor">- R$ <?php echo htmlspecialchars($descontos); ?></span>
        </div>
        <div class="linha-total subtotal">
            <span class="rotulo">Subtotal</span>
            <span class="valor">R$ <?php echo htmlspecialchars($subtotal); ?></span>
        </div>

        <button type="button" class="btn-finalizar-pedido" id="btnFinalizarPedido">Finalizar Pedido</button>

        <span class="link-saiba-mais">
            <a href="#" id="linkSaibaMais">Descubra como sua compra ajuda artistas parceiros</a>
        </span>
    </div>

</div>