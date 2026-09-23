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
    new URLSearchParams(window.location.search).get("categoriaId") ?? "todos";

  cacheProdutos.set(categoriaAtual, produtosContainer.innerHTML);

  function montarUrlPartial(categoriaId) {
    if (categoriaId === "todos") {
      return "/partials/produtos.php";
    }

    return `/partials/produtos.php?categoriaId=${encodeURIComponent(
      categoriaId,
    )}`;
  }

  function montarUrlPagina(categoriaId) {
    if (categoriaId === "todos") {
      return "/";
    }

    return `/?categoriaId=${encodeURIComponent(categoriaId)}`;
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

    const loadingTimer = setTimeout(() => {
      produtosContainer.setAttribute("aria-busy", "true");

      produtosContainer.classList.add("opacity-50");
    }, 150);

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
      clearTimeout(loadingTimer);

      if (requisicaoAtual === controller) {
        produtosContainer.removeAttribute("aria-busy");

        produtosContainer.classList.remove("opacity-50");

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

    const categoriaId = parametros.get("categoriaId") ?? "todos";

    carregarProdutos(categoriaId, false);
  });
});
