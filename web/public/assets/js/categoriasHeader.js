async function carregarCategorias() {

    const listaProdutos = document.getElementById("menu-header");

    try {

        const resposta = await fetch("http://localhost:8080/categorias");

        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }

        const categorias = await resposta.json();

        if (!categorias || categorias.length === 0) {

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

        categorias.forEach(categoria => {

            const coluna = document.createElement("div");

            console.log(categoria);

            coluna.className = "col-12 col-sm-6 col-lg-3 col-xl-2";

            coluna.innerHTML = `
                <div class="col-4">
                    <a href="" class="d-block py-1 bg-danger text-dark text-decoration-none">
                        ${categoria.nome}
                    </a>
                </div>
            `;

            listaProdutos.appendChild(coluna);
        });

    } catch (erro) {

        console.error("Erro ao carregar categorias:", erro);

        listaProdutos.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center" role="alert">
                    <strong>Categorias indisponíveis.</strong><br>
                    Não foi possível carregar as categorias no momento.
                </div>
            </div>
        `;
    }
}

carregarProdutos();