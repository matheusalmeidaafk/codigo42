
document.addEventListener('DOMContentLoaded', () => {
    // GALERIA DE IMAGENS
    const miniaturas = document.querySelectorAll('.miniatura-produto');
    const imagemPrincipal = document.getElementById('imagemPrincipal');
    const imagemAnterior = document.getElementById('imagemAnterior');
    const proximaImagem = document.getElementById('proximaImagem');

    let indiceAtual = 0;

    function trocarImagem(indice) {
        if (!imagemPrincipal || miniaturas.length === 0) return;

        indiceAtual = (indice + miniaturas.length) % miniaturas.length;

        imagemPrincipal.src = miniaturas[indiceAtual].src;

        miniaturas.forEach((miniatura, i) => {
            miniatura.classList.toggle('border-2', i === indiceAtual);
            miniatura.classList.toggle('border-dark', i === indiceAtual);
        });
    }

    miniaturas.forEach((miniatura, indice) => {
        miniatura.addEventListener('click', () => trocarImagem(indice));
    });

    imagemAnterior?.addEventListener('click', () => {
        trocarImagem(indiceAtual - 1);
    });

    proximaImagem?.addEventListener('click', () => {
        trocarImagem(indiceAtual + 1);
    });

    // QUANTIDADE
    const quantidade = document.getElementById('quantidade');
    const diminuir = document.getElementById('diminuir');
    const aumentar = document.getElementById('aumentar');

    function limitarQuantidade() {
        if (!quantidade) return;

        let valor = parseInt(quantidade.value, 10);

        if (Number.isNaN(valor)) valor = 1;

        quantidade.value = Math.max(1, Math.min(99, valor));
    }

    diminuir?.addEventListener('click', () => {
        if (!quantidade) return;

        limitarQuantidade();
        quantidade.value = Math.max(1, Number(quantidade.value) - 1);
    });

    aumentar?.addEventListener('click', () => {
        if (!quantidade) return;

        limitarQuantidade();
        quantidade.value = Math.min(99, Number(quantidade.value) + 1);
    });

    quantidade?.addEventListener('change', limitarQuantidade);

    // VALIDAÇÃO DO TAMANHO
    function adicionarCompra() {
        const selected = document.getElementById('variacaoProduto')?.selectedOptions[0];
        const aviso = document.getElementById('avisoTamanho');
        if (!selected?.value || Number(selected.dataset.estoque) <= 0) { aviso?.classList.remove('d-none'); return false; }
        aviso?.classList.add('d-none');
        const botao = document.getElementById('adicionarCarrinho');
        if (!botao || typeof window.addToCart !== 'function') return;
        limitarQuantidade();
        const size = selected.value;
        const product = {
            id: `${botao.dataset.id}:${size}`,
            name: botao.dataset.nome,
            subtitle: selected.textContent,
            stock: Number(selected.dataset.estoque),
            oldPrice: Number(botao.dataset.precoOriginal),
            price: Number(botao.dataset.preco) || 0,
            image: botao.dataset.imagem,
            quantity: Number(quantidade?.value) || 1
        };
        return window.addToCart(product);
    }

    document.getElementById('adicionarCarrinho')
        ?.addEventListener('click', adicionarCompra);

    document.getElementById('comprar')
        ?.addEventListener('click', () => { if (adicionarCompra()) location.href = '/pages/carrinho.php'; });
});
