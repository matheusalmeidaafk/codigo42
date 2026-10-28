<?php
$cartSidebarId = "carrinhoSideBar";
$cartOverlayId = "carrinhoOverlay";
$closeCartBtnId = "fecharCarrinhoBtn";
$cartItemsListId = "carrinhoItensLista";
$cartHeaderTitleId = "carrinhoTituloHeader";
$cartItemsTotalId = "carrinhoTotalItens";
$cartDiscountId = "carrinhoDesconto";
$cartSubtotalId = "carrinhoSubtotal";
$checkoutBtnId = "carrinhoFinalizarBtn";
?>

<div id="<?php echo $cartSidebarId; ?>" class="carrinho-sidebar d-flex flex-column">
    <div class="carrinho-header p-3 border-bottom d-flex justify-content-between align-items-center">
        <h3 id="<?php echo $cartHeaderTitleId; ?>" class="fs-6 fw-semibold text-dark m-0">Carrinho (0 itens)</h3>
        <button id="<?php echo $closeCartBtnId; ?>" class="carrinho-close-btn border-0 bg-transparent fs-4 text-secondary">&times;</button>
    </div>

    <div class="carrinho-body flex-grow-1 overflow-auto p-3">
        <div id="<?php echo $cartItemsListId; ?>" class="carrinho-items-list">
            <p class="carrinho-empty-msg text-center text-muted mt-4">Seu carrinho está vazio.</p>
        </div>
    </div>

    <div class="carrinho-footer p-3 border-top bg-white">
        <div class="carrinho-summary-line d-flex justify-content-between mb-2 text-muted small">
            <span>Total dos itens</span>
            <span id="<?php echo $cartItemsTotalId; ?>">R$ 0,00</span>
        </div>
        <div class="carrinho-summary-line d-flex justify-content-between mb-2 text-muted small">
            <span>Descontos</span>
            <span id="<?php echo $cartDiscountId; ?>">- R$ 0,00</span>
        </div>
        <div class="carrinho-summary-line subtotal d-flex justify-content-between fw-bold text-success border-top border-dashed pt-2 mt-2">
            <span>Subtotal</span>
            <span id="<?php echo $cartSubtotalId; ?>">R$ 0,00</span>
        </div>
        <a class="btn btn-dark w-100 mt-3" href="/pages/carrinho.php">Ver meu carrinho</a>
        <button id="<?php echo $checkoutBtnId; ?>" class="btn btn-outline-secondary w-100 fw-bold mt-3 py-2" type="button">Continuar comprando</button>
    </div>
</div>

<div id="<?php echo $cartOverlayId; ?>" class="carrinho-overlay"></div>
