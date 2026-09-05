async function carregarCategorias() {

    const listaCategorias = document.getElementById("menu-header");

    try {

        // const resposta = await fetch("http://localhost:8080/categorias");

        // if (!resposta.ok) {
        //     throw new Error(`Erro HTTP: ${resposta.status}`);
        // }


        const categorias = [
            {
                id_categoria: 1,
                nome: "Eletrônicos",
                id_categoria_pai: null
            },
            {
                id_categoria: 2,
                nome: "Roupas",
                id_categoria_pai: null
            },
            {
                id_categoria: 3,
                nome: "Acessórios",
                id_categoria_pai: null
            }
        ]


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

        listaCategorias.innerHTML = "";
        const subcategoriasTeste = [
            [
                "Celulares",
                "Computadores",
                "Televisores",
                "Monitores",
                "Notebooks",
                "Tablets",
                "Fones",
                "Teclados",
                "Mouse",
                "Impressoras"
            ],

            [
                "Camisetas",
                "Calças",
                "Tênis",
                "Jaquetas",
                "Moletons",
                "Bonés",
                "Bermudas",
                "Meias",
                "Vestidos"
            ],

            [
                "Parafusos",
                "Molas",
                "Ímãs",
                "Núcleos",
                "Porcas",
                "Arruelas",
                "Ferramentas"
            ]
        ];

        categorias.forEach((categoria, index) => {

            const coluna = document.createElement("div");

            coluna.className = "categoria-menu";

            const subcategorias = subcategoriasTeste[index] || [];

            coluna.innerHTML = `
                <a href="#" class="categoria-link">
                    ${categoria.nome}
                </a>

                <div class="subcategorias">
                    ${subcategorias.map(subcategoria => `
                        <a href="#" class="subcategoria-link">
                            ${subcategoria}
                        </a>
                    `).join("")}
                </div>
            `;

            listaCategorias.appendChild(coluna);
        });

    } catch (erro) {

        console.error("Erro ao carregar categorias:", erro);

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