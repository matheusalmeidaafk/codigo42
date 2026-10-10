// Galeria
const miniaturas = document.querySelectorAll('.miniatura-produto');
const imagemPrincipal = document.getElementById('imagemPrincipal');

let indiceAtual = 0;

function trocarImagem(indice) {
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

document.getElementById('imagemAnterior').addEventListener('click', () => {
    trocarImagem(indiceAtual - 1);
});

document.getElementById('proximaImagem').addEventListener('click', () => {
    trocarImagem(indiceAtual + 1);
});

// Quantidade
const quantidade = document.getElementById('quantidade');

document.getElementById('diminuir').addEventListener('click', () => {
    quantidade.value = Math.max(1, (parseInt(quantidade.value, 10) || 1) - 1);
});

document.getElementById('aumentar').addEventListener('click', () => {
    quantidade.value = Math.min(99, (parseInt(quantidade.value, 10) || 1) + 1);
});

quantidade.addEventListener('change', () => {
    quantidade.value = Math.max(1, Math.min(99, parseInt(quantidade.value, 10) || 1));
});

// Simulação de compra, sem backend
function simularCompra() {
    const tamanhos = [...document.querySelectorAll('.tamanho:checked')]
        .map(input => input.value);

    const aviso = document.getElementById('avisoTamanho');

    if (tamanhos.length === 0) {
        aviso.classList.remove('d-none');
        return;
    }

    aviso.classList.add('d-none');

    const total = Number(quantidade.value) * 100;

    alert(
        'Mock da loja Código 42\\n\\n' +
        'Camiseta Spike - Cowboy Bebop\\n' +
        'Tamanhos: ' + tamanhos.join(', ') + '\\n' +
        'Quantidade: ' + quantidade.value + '\\n' +
        'Total: R$ ' + total.toFixed(2).replace('.', ',')
    );
}

document.getElementById('adicionarCarrinho')
    .addEventListener('click', simularCompra);

document.getElementById('comprar')
    .addEventListener('click', simularCompra);