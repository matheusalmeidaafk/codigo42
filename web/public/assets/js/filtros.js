const precoMinInput = document.getElementById('precoMin');
const precoMaxInput = document.getElementById('precoMax');

const valorPrecoMin = document.getElementById('valorPrecoMin');
const valorPrecoMax = document.getElementById('valorPrecoMax');

const faixaPreco = document.getElementById('faixaPreco');


function formatarPreco(valor) {
    return Number(valor).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}


function atualizarPreco() {

    const min = Number(precoMinInput.value);
    const max = Number(precoMaxInput.value);

    valorPrecoMin.textContent = formatarPreco(min);
    valorPrecoMax.textContent = formatarPreco(max);


    const limiteMin = Number(precoMinInput.min);
    const limiteMax = Number(precoMinInput.max);

    const porcentagemMin =
        ((min - limiteMin) / (limiteMax - limiteMin)) * 100;

    const porcentagemMax =
        ((max - limiteMin) / (limiteMax - limiteMin)) * 100;


    faixaPreco.style.left = `${porcentagemMin}%`;
    faixaPreco.style.width = `${porcentagemMax - porcentagemMin}%`;
}


precoMinInput.addEventListener('input', function () {

    if (Number(precoMinInput.value) > Number(precoMaxInput.value)) {
        precoMinInput.value = precoMaxInput.value;
    }

    atualizarPreco();
});


precoMaxInput.addEventListener('input', function () {

    if (Number(precoMaxInput.value) < Number(precoMinInput.value)) {
        precoMaxInput.value = precoMinInput.value;
    }

    atualizarPreco();
});


atualizarPreco();