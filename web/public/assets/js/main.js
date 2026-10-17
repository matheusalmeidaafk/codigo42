document.addEventListener("DOMContentLoaded", () => {
  const navProdutos = document.querySelector(".filtros-produto");
  const produtosContainer = document.querySelector("#produtos-container");

  if (!navProdutos || !produtosContainer) {
    return;
  }

  const filtros = [...navProdutos.querySelectorAll(".filtro-produto")];

  const cacheProdutos = new Map();

  let requisicaoAtual = null;

  let categoriaAtual =
    new URLSearchParams(window.location.search).get("categorias") ?? "todos";

  function montarUrlPartial(categoriaId) {
    if (categoriaId === "todos") {
      return "/partials/produtos.php";
    }

    return `/partials/produtos.php?categorias=${encodeURIComponent(
      categoriaId,
    )}`;
  }

  function montarUrlPagina(categoriaId) {
    if (categoriaId === "todos") {
      return "/";
    }

    return `/?categorias=${encodeURIComponent(categoriaId)}`;
  }

  function atualizarFiltroAtivo(categoriaId) {
    filtros.forEach((filtro) => {
      const ativo = filtro.dataset.categoriaId === categoriaId;

      filtro.classList.toggle("btn-dark", ativo);

      filtro.classList.toggle("btn-outline-dark", !ativo);
    });
  }

  function atualizarUrl(categoriaId) {
    history.pushState(
      {
        categoriaId,
      },
      "",
      montarUrlPagina(categoriaId),
    );
  }

  // Usa as classes utilitárias do Bootstrap para o estado de carregamento.
  // Não é necessário criar CSS próprio para o spinner.
  const containerJaEraRelativo = produtosContainer.classList.contains(
    "position-relative",
  );

  function mostrarCarregamento() {
    produtosContainer.setAttribute("aria-busy", "true");
    produtosContainer.classList.add("position-relative");

    // Evita criar mais de um indicador quando uma requisição é substituída.
    if (produtosContainer.querySelector(".indicador-carregamento-produtos")) {
      return;
    }

    const indicador = document.createElement("div");
    indicador.className = [
      "indicador-carregamento-produtos",
      "position-absolute",
      "top-0",
      "bottom-0",
      "start-0",
      "end-0",
      "z-3",
      "d-flex",
      "flex-column",
      "align-items-center",
      "justify-content-center",
      "gap-2",
      "bg-white",
      "bg-opacity-75",
      "rounded",
      "text-center",
    ].join(" ");
    indicador.setAttribute("role", "status");
    indicador.setAttribute("aria-live", "polite");

    const spinner = document.createElement("span");
    spinner.className = "spinner-border text-success";
    spinner.setAttribute("aria-hidden", "true");

    const mensagem = document.createElement("span");
    mensagem.className = "text-dark fw-medium";
    mensagem.textContent = "Carregando produtos...";

    indicador.append(spinner, mensagem);
    produtosContainer.append(indicador);
  }

  function ocultarCarregamento() {
    produtosContainer.removeAttribute("aria-busy");
    produtosContainer
      .querySelector(".indicador-carregamento-produtos")
      ?.remove();

    if (!containerJaEraRelativo) {
      produtosContainer.classList.remove("position-relative");
    }
  }

  function substituirProdutos(html, categoriaId) {
    const carrosselAtual =
      produtosContainer.querySelector("#carrosselProdutos");

    if (carrosselAtual && window.bootstrap?.Carousel) {
      const instancia = bootstrap.Carousel.getInstance(carrosselAtual);

      instancia?.dispose();
    }

    produtosContainer.innerHTML = html;

    categoriaAtual = categoriaId;

    atualizarFiltroAtivo(categoriaId);
  }

  async function carregarProdutos(categoriaId, atualizarHistorico = true) {
    if (requisicaoAtual) {
      requisicaoAtual.abort();
    }

    if (categoriaId === categoriaAtual) {
      return;
    }

    if (cacheProdutos.has(categoriaId)) {
      substituirProdutos(cacheProdutos.get(categoriaId), categoriaId);

      if (atualizarHistorico) {
        atualizarUrl(categoriaId);
      }

      return;
    }

    const controller = new AbortController();

    requisicaoAtual = controller;

    const urlPartial = montarUrlPartial(categoriaId);

    mostrarCarregamento();

    try {
      const resposta = await fetch(urlPartial, {
        signal: controller.signal,

        headers: {
          "X-Requested-With": "fetch",
        },
      });

      if (!resposta.ok) {
        throw new Error("Erro ao carregar produtos.");
      }

      const html = await resposta.text();

      cacheProdutos.set(categoriaId, html);

      substituirProdutos(html, categoriaId);

      if (atualizarHistorico) {
        atualizarUrl(categoriaId);
      }
    } catch (erro) {
      if (erro.name === "AbortError") {
        return;
      }

      console.error(erro);

      const filtroSelecionado = filtros.find(
        (filtro) => filtro.dataset.categoriaId === categoriaId,
      );

      if (filtroSelecionado) {
        window.location.href = filtroSelecionado.href;
      }
    } finally {
      if (requisicaoAtual === controller) {
        ocultarCarregamento();
        requisicaoAtual = null;
      }
    }
  }

  filtros.forEach((filtro) => {
    filtro.addEventListener("click", (evento) => {
      evento.preventDefault();

      const categoriaId = filtro.dataset.categoriaId;

      carregarProdutos(categoriaId);
    });
  });

  window.addEventListener("popstate", () => {
    const parametros = new URLSearchParams(window.location.search);

    const categoriaId = parametros.get("categorias") ?? "todos";

    carregarProdutos(categoriaId, false);
  });
});
