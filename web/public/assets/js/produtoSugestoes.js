(() => {
    "use strict";

    const secao = document.querySelector("#produto-sugestoes");
    if (!secao) return;

    const API_BASE_URL = "http://localhost:8080";
    const URL_CARROSSEL = "/partials/carrosselSugestoes.php";
    const LIMITE_SUGESTOES = 10;
    const produtoAtualId = Number.parseInt(secao.dataset.produtoId || "0", 10);
    const categoriaIds = [...new Set(
        (secao.dataset.categoriaIds || "")
            .split(",")
            .map((id) => Number.parseInt(id.trim(), 10))
            .filter((id) => Number.isInteger(id) && id > 0)
    )];
    const termoRelacionado = (secao.dataset.termoRelacionado || "").trim();

    const status = secao.querySelector("[data-sugestoes-status]");
    const destinoCarrossel = secao.querySelector("[data-sugestoes-renderizado]");
    if (!status || !destinoCarrossel) return;

    function normalizarProduto(produto) {
        const preco = Number(produto.preco ?? 0);
        const precoFinal = Number(produto.preco_final ?? preco);
        const descontoInformado = produto.porcentagem_desconto;
        const imagem = produto.variacao_imagem_url
            ?? produto.imagem_url
            ?? "https://placehold.co/600x600?text=Sem+Imagem";

        return {
            ...produto,
            id_produto: Number(produto.id_produto ?? produto.id ?? 0),
            nome: String(produto.nome ?? "Produto"),
            preco: Number.isFinite(preco) ? preco : 0,
            preco_final: Number.isFinite(precoFinal) ? precoFinal : 0,
            estrelas: Math.max(0, Math.min(5, Number.parseInt(produto.estrelas ?? 0, 10) || 0)),
            porcentagem_desconto: descontoInformado === null || descontoInformado === undefined
                ? null
                : (Number.isFinite(Number(descontoInformado)) ? Number(descontoInformado) : null),
            variacao_imagem_url: String(imagem),
            tamanhos: Array.isArray(produto.tamanhos)
                ? produto.tamanhos
                : (produto.tamanho ? [String(produto.tamanho)] : [])
        };
    }

    async function buscarProdutos(url) {
        const resposta = await fetch(url, {
            method: "GET",
            headers: { Accept: "application/json" }
        });

        if (!resposta.ok) {
            throw new Error(`A API retornou HTTP ${resposta.status}.`);
        }

        const dados = await resposta.json();
        if (!Array.isArray(dados)) {
            throw new Error("A API retornou uma lista de produtos inválida.");
        }

        return dados.map(normalizarProduto);
    }

    function embaralhar(produtos) {
        // Fisher-Yates: embaralha a lista no frontend.
        const resultado = [...produtos];
        for (let i = resultado.length - 1; i > 0; i -= 1) {
            const j = Math.floor(Math.random() * (i + 1));
            [resultado[i], resultado[j]] = [resultado[j], resultado[i]];
        }
        return resultado;
    }

    function combinarSugestoes(produtosCategoria, produtosRelacionados) {
        const unicos = new Map();

        [...produtosCategoria, ...produtosRelacionados].forEach((produto) => {
            if (produtoAtualId > 0 && produto.id_produto === produtoAtualId) return;

            const nomeNormalizado = produto.nome.trim().toLocaleLowerCase("pt-BR");
            const chave = produto.id_produto > 0
                ? `id-${produto.id_produto}`
                : `nome-${nomeNormalizado}`;

            if (chave === "nome-") return;
            // O produto pode vir repetido por ter várias variações.
            if (!unicos.has(chave)) unicos.set(chave, produto);
        });

        return embaralhar([...unicos.values()]).slice(0, LIMITE_SUGESTOES);
    }

    async function renderizarComponenteCarrossel(produtos) {
        const resposta = await fetch(URL_CARROSSEL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "text/html"
            },
            body: JSON.stringify({ produtos })
        });

        if (!resposta.ok) {
            throw new Error(`Não foi possível renderizar o carrossel (HTTP ${resposta.status}).`);
        }

        destinoCarrossel.innerHTML = await resposta.text();
        status.classList.add("d-none");
    }

    async function carregarSugestoes() {
        const buscas = [];

        // Busca 1: produtos das categorias do produto atual.
        if (categoriaIds.length > 0) {
            const query = encodeURIComponent(categoriaIds.join(","));
            buscas.push({
                tipo: "categoria",
                promessa: buscarProdutos(`${API_BASE_URL}/produtos?categorias=${query}`)
            });
        }

        // Busca 2: produtos encontrados pelo tema/personagem/franquia.
        if (termoRelacionado) {
            buscas.push({
                tipo: "relacionados",
                promessa: buscarProdutos(`${API_BASE_URL}/produtos/${encodeURIComponent(termoRelacionado)}`)
            });
        }

        // Fallback: quando a URL não informa categorias nem termo,
        // usa a listagem geral para que a seção não fique vazia.
        if (buscas.length === 0) {
            buscas.push({
                tipo: "geral",
                promessa: buscarProdutos(`${API_BASE_URL}/produtos`)
            });
        }

        const resultados = await Promise.allSettled(buscas.map((busca) => busca.promessa));
        let produtosCategoria = [];
        let produtosRelacionados = [];
        let buscasConcluidas = 0;

        resultados.forEach((resultado, indice) => {
            if (resultado.status !== "fulfilled") {
                console.error(`[SUGESTÕES] Falha na busca ${buscas[indice].tipo}:`, resultado.reason);
                return;
            }

            buscasConcluidas += 1;
            if (buscas[indice].tipo === "categoria") produtosCategoria = resultado.value;
            if (buscas[indice].tipo === "relacionados") produtosRelacionados = resultado.value;
            if (buscas[indice].tipo === "geral") produtosCategoria = resultado.value;
        });

        if (buscasConcluidas === 0) {
            throw new Error("As buscas de sugestões falharam.");
        }

        const sugestoes = combinarSugestoes(produtosCategoria, produtosRelacionados);
        if (sugestoes.length === 0) {
            status.textContent = "Não encontramos sugestões para este produto no momento.";
            return;
        }

        await renderizarComponenteCarrossel(sugestoes);
    }

    carregarSugestoes().catch((erro) => {
        console.error("[SUGESTÕES DE PRODUTO]", erro);
        status.textContent = "Não foi possível carregar as sugestões agora.";
    });
})();