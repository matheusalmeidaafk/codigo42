
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
    function simularCompra() {
        const tamanhoSelecionado = document.querySelector('.tamanho:checked');
        const aviso = document.getElementById('avisoTamanho');

        if (!tamanhoSelecionado) {
            aviso?.classList.remove('d-none');
            return;
        }

        aviso?.classList.add('d-none');

        alert(
            'Código 42\n\n' +
            'Tamanho: ' + tamanhoSelecionado.value + '\n' +
            'Quantidade: ' + (quantidade?.value || 1)
        );
    }

    document.getElementById('adicionarCarrinho')
        ?.addEventListener('click', simularCompra);

    document.getElementById('comprar')
        ?.addEventListener('click', simularCompra);
});
