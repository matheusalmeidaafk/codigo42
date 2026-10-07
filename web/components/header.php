<header class="bg-dark text-light">
    <div class="container-fluid px-5 py-3">
        <div class="row align-items-center">

            <div class="col-4 col-md-3">
                <a href="../index.php">
                    <img src="../assets/images/logo.png" alt="Código 42" class="img-fluid logo">
                </a>
            </div>

            <div class="col-8 col-md-6">
                <form class="d-flex mx-auto searchBar" role="search">
                    <input class="form-control me-2 bg-dark text-light border-secondary" type="search"
                        placeholder="Search" aria-label="Search">

                    <button class="btn btn-outline-success" type="submit">
                        Search
                    </button>
                </form>
            </div>

            <div class="col-12 col-md-3 mt-3 mt-md-0">
                <div class="d-flex justify-content-md-end justify-content-center align-items-center gap-3">

                    <a href="" class="text-light text-decoration-none fw-semibold sobre">
                        Sobre
                    </a>

                    <a href="./pages/login.php" class="text-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-person-fill" viewBox="0 0 16 16">

                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                        </svg>
                    </a>

                    <a href="" class="text-light mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-basket-fill" viewBox="0 0 16 16">

                            <path
                                d="M5.071 1.243a.5.5 0 0 1 .858.514L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 6h1.717zM3.5 10.5a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="menu-header" class="row g-0 text-center menu-header">

    </div>
    
</header>

<script>
    function renderizarCategorias(categorias) {
        const listaCategorias = document.getElementById("menu-header");

        listaCategorias.innerHTML = "";

        categorias.forEach((categoria) => {
            const coluna = document.createElement("div");

            coluna.className = "categoria-menu";

            coluna.innerHTML = `
                <a href="#" 
                   class="categoria-link"
                   data-id="${categoria.id_categoria}">
                    ${categoria.nome}
                </a>

                <div class="subcategorias"></div>
            `;

            const link = coluna.querySelector(".categoria-link");
            const subcategorias = coluna.querySelector(".subcategorias");

            let carregada = false;
            let carregando = false;

            link.addEventListener("mouseenter", async () => {

                // Evita fazer a mesma requisição novamente
                if (carregada || carregando) {
                    return;
                }

                carregando = true;

                try {
                    const idCategoria = categoria.id_categoria;

                    const resposta = await fetch(
                        `http://localhost:8080/subcategoria/${idCategoria}`
                    );

                    if (!resposta.ok) {
                        throw new Error(
                            `Erro HTTP: ${resposta.status}`
                        );
                    }

                    const categoriasFilhas = await resposta.json();

                    subcategorias.innerHTML = "";

                    if (!categoriasFilhas || categoriasFilhas.length === 0) {
                        carregada = true;
                        return;
                    }

                    categoriasFilhas.forEach((subcategoria) => {

                        const linkSubcategoria =
                            document.createElement("a");

                        linkSubcategoria.href =
                            `#`;

                        linkSubcategoria.className =
                            "subcategoria-link";

                        linkSubcategoria.textContent =
                            subcategoria.nome;

                        subcategorias.appendChild(
                            linkSubcategoria
                        );
                    });

                    carregada = true;

                } catch (erro) {

                    console.error(
                        `Erro ao carregar subcategorias da categoria ${categoria.id_categoria}:`,
                        erro
                    );

                    subcategorias.innerHTML = `
                        <span class="subcategoria-erro">
                            Não foi possível carregar.
                        </span>
                    `;

                } finally {
                    carregando = false;
                }
            });

            listaCategorias.appendChild(coluna);
        });
    }


    async function carregarCategorias() {

        const listaCategorias =
            document.getElementById("menu-header");

        const categoriasEstaticas = [
            {
                id_categoria: 1,
                nome: "Canecas",
                id_categoria_pai: null
            },
            {
                id_categoria: 2,
                nome: "Adesivos",
                id_categoria_pai: null
            },
            {
                id_categoria: 3,
                nome: "Camisetas",
                id_categoria_pai: null
            }
        ];

        // Renderiza temporariamente as categorias estáticas
        renderizarCategorias(categoriasEstaticas);

        try {

            const resposta = await fetch(
                "http://localhost:8080/categoriasPai"
            );

            if (!resposta.ok) {
                throw new Error(
                    `Erro HTTP: ${resposta.status}`
                );
            }

            const categorias = await resposta.json();

            console.log("Categorias:", categorias);

            if (!categorias || categorias.length === 0) {

                listaCategorias.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-secondary text-center">
                            Nenhuma categoria disponível no momento.
                        </div>
                    </div>
                `;

                return;
            }

            renderizarCategorias(categorias);

        } catch (erro) {

            console.error(
                "Erro ao carregar categorias:",
                erro
            );

            listaCategorias.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger text-center">
                        <strong>Categorias indisponíveis.</strong><br>
                        Não foi possível carregar as categorias no momento.
                    </div>
                </div>
            `;
        }
    }


    carregarCategorias();
</script>