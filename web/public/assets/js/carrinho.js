document.addEventListener("DOMContentLoaded", () => {
    function normalizeCart(items) {
        return (Array.isArray(items) ? items : []).filter(item => item && item.id && Number.isFinite(Number(item.price)) && Number(item.price) >= 0).map(item => ({
            ...item, price: Number(item.price), oldPrice: Math.max(Number(item.price), Number(item.oldPrice) || Number(item.price)),
            quantity: Math.max(1, Math.min(99, Math.floor(Number(item.quantity) || 1)))
        }));
    }
    const money = value => value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    let cart;
    try {
        const saved = JSON.parse(localStorage.getItem("ecommerce_cart"));
        cart = normalizeCart(saved);
    } catch { cart = []; }
    const escapeHTML = value => String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[char]);

    const carrinhoSideBar = document.getElementById("carrinhoSideBar");
    const carrinhoOverlay = document.getElementById("carrinhoOverlay");
    const fecharCarrinhoBtn = document.getElementById("fecharCarrinhoBtn");
    const carrinhoItensLista = document.getElementById("carrinhoItensLista");
    const carrinhoTituloHeader = document.getElementById("carrinhoTituloHeader");
    const carrinhoTotalItens = document.getElementById("carrinhoTotalItens");
    const carrinhoDesconto = document.getElementById("carrinhoDesconto");
    const carrinhoSubtotal = document.getElementById("carrinhoSubtotal");

    // Usa querySelectorAll para capturar qualquer elemento que sirva de gatilho para abrir o carrinho
    const cartTriggers = document.querySelectorAll(".js-abrir-carrinho, #abrirCarrinho, .fa-shopping-cart, .bi-basket-fill");

    function saveCart() {
        localStorage.setItem("ecommerce_cart", JSON.stringify(cart));
        updateCartUI();
        renderPage();
    }

    function updateCartUI() {
        const totalItemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        if (carrinhoTituloHeader) {
            carrinhoTituloHeader.textContent = `Carrinho (${totalItemsCount} ${totalItemsCount === 1 ? 'item' : 'itens'})`;
        }

        updateHeaderBadge(totalItemsCount);

        if (!carrinhoItensLista) return;

        if (cart.length === 0) {
            carrinhoItensLista.innerHTML = '<p class="carrinho-empty-msg">Seu carrinho está vazio.</p>';
            if (carrinhoTotalItens) carrinhoTotalItens.textContent = "R$ 0,00";
            if (carrinhoDesconto) carrinhoDesconto.textContent = "- R$ 0,00";
            if (carrinhoSubtotal) carrinhoSubtotal.textContent = "R$ 0,00";
            return;
        }

        carrinhoItensLista.innerHTML = "";
        let grossTotal = 0;
        let totalDiscount = 0;

        cart.forEach((item, index) => {
            const itemPrice = item.price || 0;
            const itemOldPrice = item.oldPrice || itemPrice;
            
            grossTotal += itemOldPrice * item.quantity;
            totalDiscount += (itemOldPrice - itemPrice) * item.quantity;

            const itemElement = document.createElement("div");
            itemElement.classList.add("carrinho-item");
            itemElement.innerHTML = `
                <img src="${escapeHTML(item.image)}" alt="${escapeHTML(item.name)}">
                <div class="carrinho-item-details">
                    <h4 class="carrinho-item-title">${escapeHTML(item.name)}</h4>
                    <p class="carrinho-item-subtitle">${escapeHTML(item.subtitle || 'Produto Exclusivo')}</p>
                    <div class="carrinho-item-prices">
                        <span class="carrinho-item-old-price">R$ ${itemOldPrice.toFixed(2).replace(".", ",")}</span>
                        <span class="carrinho-item-price">R$ ${itemPrice.toFixed(2).replace(".", ",")}</span>
                    </div>
                    <div class="carrinho-item-actions-row">
                        <div class="carrinho-item-controls">
                            <button type="button" onclick="window.updateItemQuantity(${index}, -1)">-</button>
                            <span>${item.quantity}</span>
                            <button type="button" onclick="window.updateItemQuantity(${index}, 1)">+</button>
                        </div>
                        <button type="button" class="carrinho-item-remove" onclick="window.removeItem(${index})" title="Remover item">
                            🗑️
                        </button>
                    </div>
                </div>
            `;
            carrinhoItensLista.appendChild(itemElement);
        });

        const netSubtotal = grossTotal - totalDiscount;
        if (carrinhoTotalItens) carrinhoTotalItens.textContent = money(grossTotal);
        if (carrinhoDesconto) carrinhoDesconto.textContent = `- ${money(totalDiscount)}`;
        if (carrinhoSubtotal) carrinhoSubtotal.textContent = money(netSubtotal);
    }

    function updateHeaderBadge(count) {
        let badge = document.getElementById("cartBadgeCount");
        const firstTrigger = cartTriggers.length > 0 ? cartTriggers[0] : null;
        
        if (!badge && firstTrigger) {
            badge = document.createElement("span");
            badge.id = "cartBadgeCount";
            badge.className = "cart-badge";
            firstTrigger.style.position = "relative";
            firstTrigger.appendChild(badge);
        }
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? "inline-flex" : "none";
        }
    }

    window.updateItemQuantity = function(index, change) {
        if (!cart[index]) return;
        const limit = Math.min(99, Number(cart[index].stock) || 99);
        if (cart[index].quantity + change > limit) { alert('Quantidade máxima disponível atingida.'); return; }
        cart[index].quantity = Math.min(limit, cart[index].quantity + change);
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
        saveCart();
    };

    window.removeItem = function(index) {
        if (!cart[index]) return;
        cart.splice(index, 1);
        saveCart();
    };

    window.addToCart = function(product) {
        if (!product?.id || !Number.isFinite(Number(product.price)) || Number(product.price) < 0) return;
        product.quantity = Math.max(1, Math.min(99, Math.floor(Number(product.quantity) || 1)));
        const existingIndex = cart.findIndex(item => item.id === product.id);
        const total = product.quantity + (cart[existingIndex]?.quantity || 0);
        if (total > Math.min(99, Number(product.stock) || 99)) { alert('Quantidade maior que o estoque disponível.'); return false; }
        if (existingIndex > -1) {
            cart[existingIndex].quantity = Math.min(99, cart[existingIndex].quantity + product.quantity);
        } else {
            cart.push(...normalizeCart([product]));
        }
        saveCart();
        openCart();
        return true;
    };

    function openCart() {
        if (carrinhoSideBar) carrinhoSideBar.classList.add("open");
        if (carrinhoOverlay) carrinhoOverlay.classList.add("open");
    }

    function closeCart() {
        if (carrinhoSideBar) carrinhoSideBar.classList.remove("open");
        if (carrinhoOverlay) carrinhoOverlay.classList.remove("open");
    }

    // Adiciona o evento de clique em todos os possíveis gatilhos do carrinho encontrados
    cartTriggers.forEach(trigger => {
        trigger.addEventListener("click", (e) => {
            e.preventDefault();
            openCart();
        });
    });

    // Cards de produtos são atualizados por AJAX; a delegação também cobre os novos cards.
    document.addEventListener('click', event => {
        const sizeButton = event.target.closest('.tamanho-btn');
        if (sizeButton) {
            event.preventDefault();
            const card = sizeButton.closest('article');
            card?.querySelectorAll('.tamanho-btn').forEach(button => {
                button.classList.toggle('active', button === sizeButton);
                button.setAttribute('aria-pressed', button === sizeButton ? 'true' : 'false');
            });
            return;
        }
        const button = event.target.closest('.js-add-cart');
        if (!button) return;
        event.preventDefault();
        location.href = '/pages/produto.php?id=' + encodeURIComponent(button.dataset.id);

    });

    if (fecharCarrinhoBtn) {
        fecharCarrinhoBtn.addEventListener("click", closeCart);
    }
    
    if (carrinhoOverlay) {
        carrinhoOverlay.addEventListener("click", closeCart);
    }
    document.getElementById('carrinhoFinalizarBtn')?.addEventListener('click', closeCart);

    function renderPage() {
        const page = document.getElementById('carrinhoPagina');
        if (!page) return;
        page.innerHTML = carrinhoItensLista.innerHTML;
        const summary = document.createElement('p'); summary.className = 'fw-bold fs-4 border-top pt-3 mt-3';
        summary.textContent = 'Subtotal: ' + money(cart.reduce((sum, item) => sum + item.price * item.quantity, 0));
        page.append(summary);
        document.getElementById('limparCarrinho').disabled = !cart.length;
    }
    document.getElementById('limparCarrinho')?.addEventListener('click', () => {
        if (confirm('Remover todos os itens do carrinho?')) { cart = []; saveCart(); }
    });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') closeCart(); });
    window.addEventListener('storage', event => {
        if (event.key !== 'ecommerce_cart') return;
        try { cart = normalizeCart(JSON.parse(event.newValue)); } catch { cart = []; }
        updateCartUI(); renderPage();
    });
    updateCartUI(); renderPage();
});
