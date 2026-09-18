document.addEventListener("DOMContentLoaded", () => {
  const vitrines = document.querySelectorAll(".vitrine");

  vitrines.forEach((vitrine) => {
    const track = vitrine.querySelector(".vitrine-track");

    const botaoAnterior = vitrine.querySelector(".vitrine-anterior");

    const botaoProximo = vitrine.querySelector(".vitrine-proximo");

    const filtros = vitrine.querySelectorAll(".filtro-produto");

    /*
     * FILTROS
     */

    filtros.forEach((botao) => {
      botao.addEventListener("click", () => {
        const categoriaSelecionada = botao.dataset.categoria;

        filtros.forEach((filtro) => {
          filtro.classList.remove("btn-dark");

          filtro.classList.add("btn-outline-dark");
        });

        botao.classList.remove("btn-outline-dark");

        botao.classList.add("btn-dark");

        const produtos = track.querySelectorAll(".produto-item");

        produtos.forEach((produto) => {
          const categoriaProduto = produto.dataset.categoria;

          const mostrar =
            categoriaSelecionada === "todos" ||
            categoriaProduto === categoriaSelecionada;

          produto.classList.toggle("d-none", !mostrar);
        });

        track.scrollTo({
          left: 0,
          behavior: "smooth",
        });
      });
    });

    /*
     * CARROSSEL
     */

    botaoAnterior.addEventListener("click", () => {
      track.scrollBy({
        left: -(track.clientWidth * 0.75),
        behavior: "smooth",
      });
    });

    botaoProximo.addEventListener("click", () => {
      track.scrollBy({
        left: track.clientWidth * 0.75,
        behavior: "smooth",
      });
    });
  });
});
