document.addEventListener("DOMContentLoaded", () => {
  const vitrines = document.querySelectorAll(".vitrine");

  vitrines.forEach((vitrine) => {
    const track = vitrine.querySelector(".vitrine-track");
    const botaoAnterior = vitrine.querySelector(".vitrine-anterior");
    const botaoProximo = vitrine.querySelector(".vitrine-proximo");
    const filtros = vitrine.querySelectorAll(".filtro-produto");

    if (!track) {
      return;
    }

    filtros.forEach((botao) => {
      botao.addEventListener("click", () => {
        const categoriaSelecionada = botao.dataset.categoriaId;

        filtros.forEach((filtro) => {
          filtro.classList.remove("btn-dark");
          filtro.classList.add("btn-outline-dark");
        });

        botao.classList.remove("btn-outline-dark");
        botao.classList.add("btn-dark");

        const produtos = track.querySelectorAll(".produto-item");

        produtos.forEach((produto) => {
          const categoriasProduto = (produto.dataset.categorias || "")
            .split(",")
            .filter(Boolean);

          const mostrar =
            categoriaSelecionada === "todos" ||
            categoriasProduto.includes(categoriaSelecionada);

          produto.classList.toggle("d-none", !mostrar);
        });

        track.scrollTo({
          left: 0,
          behavior: "smooth",
        });
      });
    });

    botaoAnterior?.addEventListener("click", () => {
      track.scrollBy({
        left: -(track.clientWidth * 0.75),
        behavior: "smooth",
      });
    });

    botaoProximo?.addEventListener("click", () => {
      track.scrollBy({
        left: track.clientWidth * 0.75,
        behavior: "smooth",
      });
    });
  });
});
