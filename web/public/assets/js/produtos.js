async function carregarProdutos() {

    const listaProdutos = document.getElementById("lista-produtos");

    listaProdutos.innerHTML = `
        <div class="col-12">
            <div class="d-flex flex-column align-items-center justify-content-center py-5">

                <div class="spinner-border text-dark mb-3" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>

                <p class="mb-0 text-muted">
                    Carregando produtos...
                </p>

            </div>
        </div>
    `;

    try {

        const resposta = await fetch("http://localhost:8000/produtos");

        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }

        const produtos = await resposta.json();

        if (!produtos || produtos.length === 0) {

            listaProdutos.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-secondary text-center" role="alert">
                        Nenhum produto disponível no momento.
                    </div>
                </div>
            `;

            return;
        }

        listaProdutos.innerHTML = "";

        produtos.forEach(produto => {

            const coluna = document.createElement("div");

            coluna.className = "col-12 col-sm-6 col-lg-3 col-xl-2";

            coluna.innerHTML = `
                <article class="card-produto h-100 border border-dark overflow-hidden">

                    <div class="card-produto-imagem">
                        
                    <!-- Imagem mockada -->

                        <img
                            src="assets/images/imagem.png"
                            alt="${produto.nome}"
                            class="card-produto-img"
                        >
                    </div>

                    <div class="card-produto-info">

                        <div class="card-produto-nome">
                            ${produto.nome}
                        </div>

                        <div class="card-produto-preco">
                            <span>Por:</span>
                            R$ ${Number(produto.preco)
                                .toFixed(2)
                                .replace(".", ",")}
                        </div>

                    </div>

                    <div class="card-produto-hover">

                        <button
                            type="button"
                            class="btn btn-success card-produto-carrinho"
                            data-id="${produto.id}"
                        >
                            Adicionar ao carrinho
                        </button>

                    </div>

                </article>
            `;

            listaProdutos.appendChild(coluna);
        });

    } catch (erro) {

        console.error("Erro ao carregar produtos:", erro);

        listaProdutos.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center" role="alert">
                    <strong>Produtos indisponíveis.</strong><br>
                    Não foi possível carregar os produtos no momento.
                </div>
            </div>
        `;
    }
}

carregarProdutos();