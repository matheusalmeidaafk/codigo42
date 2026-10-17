
(() => {
    "use strict";

    const carrosseisConfigurados = new WeakMap();

    function cardsPorSlide() {
        if (window.innerWidth < 576) return 2;
        if (window.innerWidth < 992) return 3;
        return 5;
    }

    function configurarCarrossel(carrossel) {
        const inner = carrossel.querySelector(".carousel-inner");

        if (!inner || carrosseisConfigurados.has(carrossel)) {
            return;
        }

        let quantidadeAtual = 0;
        let cards = [];

        function reagrupar(forcar = false) {
            const quantidade = cardsPorSlide();

            if (!forcar && quantidade === quantidadeAtual) {
                return;
            }

            // Recupera os cards, independentemente do slide atual.
            if (!forcar) {
                cards = [
                    ...inner.querySelectorAll(".produto-carrossel-item")
                ];
            }

            if (cards.length === 0) return;

            const slideAtivo = inner.querySelector(
                ".carousel-item.active"
            );

            const primeiroCardAtivo = slideAtivo?.querySelector(
                ".produto-carrossel-item"
            );

            const indiceAtivo = Math.max(
                0,
                cards.indexOf(primeiroCardAtivo)
            );

            // Interrompe a instância antes de reconstruir os slides.
            const instancia = bootstrap.Carousel.getInstance(carrossel);
            instancia?.dispose();

            inner.replaceChildren();

            for (let i = 0; i < cards.length; i += quantidade) {
                const slide = document.createElement("div");
                slide.className = "carousel-item";

                if (i === 0) {
                    slide.classList.add("active");
                }

                const linha = document.createElement("div");
                linha.className = "d-flex flex-nowrap gap-1";

                cards.slice(i, i + quantidade).forEach((card) => {
                    linha.appendChild(card);
                });

                slide.appendChild(linha);
                inner.appendChild(slide);
            }

            // Mantém o produto ativo dentro do novo agrupamento.
            const novoIndice = Math.floor(indiceAtivo / quantidade);
            const slides = inner.querySelectorAll(".carousel-item");

            slides.forEach((slide, indice) => {
                slide.classList.toggle("active", indice === novoIndice);
            });

            quantidadeAtual = quantidade;

            bootstrap.Carousel.getOrCreateInstance(carrossel, {
                interval: false,
                ride: false,
                wrap: true
            });
        }

        carrosseisConfigurados.set(carrossel, true);

        // Guarda os cards originais para os próximos redimensionamentos.
        cards = [
            ...inner.querySelectorAll(".produto-carrossel-item")
        ];

        reagrupar(true);

        let larguraAnterior = cardsPorSlide();

        window.addEventListener("resize", () => {
            const novaLargura = cardsPorSlide();

            if (novaLargura === larguraAnterior) return;

            larguraAnterior = novaLargura;
            reagrupar();
        });
    }

    function procurarCarrosseis(raiz = document) {
        if (raiz.matches?.(".carousel")) {
            configurarCarrossel(raiz);
        }

        raiz.querySelectorAll?.(".carousel").forEach(
            configurarCarrossel
        );
    }

    document.addEventListener("DOMContentLoaded", () => {
        procurarCarrosseis();

        // Também configura carrosséis inseridos dinamicamente,
        // como as sugestões carregadas via fetch.
        const observador = new MutationObserver((alteracoes) => {
            alteracoes.forEach((alteracao) => {
                alteracao.addedNodes.forEach((no) => {
                    if (no.nodeType === Node.ELEMENT_NODE) {
                        procurarCarrosseis(no);
                    }
                });
            });
        });

        observador.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
})();
