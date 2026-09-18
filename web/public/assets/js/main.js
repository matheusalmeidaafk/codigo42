document.addEventListener("DOMContentLoaded", () => {
  const botoesFiltro = document.querySelectorAll(".filtroProduto");
  const produtos = document.querySelectorAll(".produtoItem");

  botoesFiltro.forEach((botao) => {
    botao.addEventListener("click", () => {
      const categoriaSelecionada = botao.dataset.categoria;

      botoesFiltro.forEach((item) => {
        item.classList.remove("ativo");
      });

      botao.classList.add("ativo");

      produtos.forEach((produto) => {
        const categoriaProduto = produto.dataset.categoria;

        const deveMostrar =
          categoriaSelecionada === "todos" ||
          categoriaProduto === categoriaSelecionada;

        produto.classList.toggle("d-none", !deveMostrar);
      });
    });
  });
});
