<?php require_once __DIR__ . '/searchBar.php'; ?>
<link rel="stylesheet" href="/assets/css/carrinhoSideBar.css">

<header class="bg-dark text-light">
    <div class="container-fluid px-5 py-3">
        <div class="row align-items-center">

            <div class="col-4 col-md-3">
                <a href="/pages/home.php">
                    <img src="/assets/images/logo.png" alt="Código 42" class="img-fluid logo">
                </a>
            </div>

            <div class="col-8 col-md-6">
                <?php renderSearchBar(is_string($_GET['q'] ?? null) ? $_GET['q'] : ''); ?>
            </div>

            <div class="col-12 col-md-3 mt-3 mt-md-0">
                <div class="d-flex justify-content-md-end justify-content-center align-items-center gap-3">

                    <a href="/pages/sobre.php" class="text-light text-decoration-none fw-semibold sobre">
                        Sobre
                    </a>

                    <a id="linkConta" href="/pages/acesso.php" class="btn btn-outline-light btn-sm px-3" aria-label="Entrar na conta">Acesse</a>

                    <a href="/pages/carrinho.php" aria-label="Abrir carrinho" class="text-light mb-1 js-abrir-carrinho" id="abrirCarrinho">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-basket-fill" viewBox="0 0 16 16">
                            <path d="M5.071 1.243a.5.5 0 0 1 .858.514L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 6h1.717zM3.5 10.5a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0zm2.5 0a.5.5 0 1 0-1 0v3a.5.5 0 0 0 1 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="menu-header" class="row g-0 text-center menu-header">

    </div>

</header>
<?php require __DIR__ . '/carrinhoSideBar.php'; ?>
<script src="/assets/js/auth.js" defer></script>
<script src="/assets/js/carrinho.js" defer></script>

<script src="/assets/js/menu.js" defer></script>
