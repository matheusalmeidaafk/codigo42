document.addEventListener("DOMContentLoaded", () => {
    let cart = JSON.parse(localStorage.getItem("ecommerce_cart")) || [];

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
            const itemOldPrice = item.oldPrice || (itemPrice * 1.25);
            
            grossTotal += itemOldPrice * item.quantity;
            totalDiscount += (itemOldPrice - itemPrice) * item.quantity;

            const itemElement = document.createElement("div");
            itemElement.classList.add("carrinho-item");
            itemElement.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <div class="carrinho-item-details">
                    <h4 class="carrinho-item-title">${item.name}</h4>
                    <p class="carrinho-item-subtitle">${item.subtitle || 'Produto Exclusivo'}</p>
                    <div class="carrinho-item-prices">
                        <span class="carrinho-item-old-price">R$ ${itemOldPrice.toFixed(2)}</span>
                        <span class="carrinho-item-price">R$ ${itemPrice.toFixed(2)}</span>
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
        if (carrinhoTotalItens) carrinhoTotalItens.textContent = `R$ ${grossTotal.toFixed(2)}`;
        if (carrinhoDesconto) carrinhoDesconto.textContent = `- R$ ${totalDiscount.toFixed(2)}`;
        if (carrinhoSubtotal) carrinhoSubtotal.textContent = `R$ ${netSubtotal.toFixed(2)}`;
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
        cart[index].quantity += change;
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
        saveCart();
    };

    window.removeItem = function(index) {
        cart.splice(index, 1);
        saveCart();
    };

    window.addToCart = function(product) {
        const existingIndex = cart.findIndex(item => item.id === product.id);
        if (existingIndex > -1) {
            cart[existingIndex].quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        saveCart();
        openCart(); // Abre o carrinho automaticamente ao adicionar um item
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

    if (fecharCarrinhoBtn) {
        fecharCarrinhoBtn.addEventListener("click", closeCart);
    }
    
    if (carrinhoOverlay) {
        carrinhoOverlay.addEventListener("click", closeCart);
    }

    updateCartUI();
});