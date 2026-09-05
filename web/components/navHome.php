<?php
/**
 * nav.php (versão Bootstrap)
 * ------------------------------------------------------------
 * Componente de FRONT-END responsável pelo cabeçalho de
 * navegação do site, dividido em duas partes:
 *
 *  1) Navbar principal: logo, busca, link "Sobre",
 *     ícone de usuário e ícone de carrinho.
 *  2) Faixa de categorias: abas coloridas
 *     (Camisetas / Canecas / Adesivos).
 *
 * IMPORTANTE:
 * - Este arquivo não recebe nem busca dados de back-end.
 * - Os links (#) e o array de categorias são placeholders —
 *   quando o back-end estiver pronto, é só trocar os href
 *   e o array $categorias pelos dados/rotas reais.
 * - Requer Bootstrap 5 CSS + Bootstrap Icons carregados na
 *   página (veja os <link> logo abaixo).
 * ------------------------------------------------------------
 */

// ------------------------------------------------------------
// DADOS DE EXEMPLO (placeholder) — viriam do back-end no futuro
// ------------------------------------------------------------
$categorias = [
    ['nome' => 'CAMISETAS', 'cor' => '#2f9e44', 'href' => '#'],
    ['nome' => 'CANECAS',   'cor' => '#f4b400', 'href' => '#'],
    ['nome' => 'ADESIVOS',  'cor' => '#e64980', 'href' => '#'],
];
?>
<!-- Bootstrap 5 CSS + Bootstrap Icons (necessário apenas uma vez na página) -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
>

<header>

    <!-- ================= NAVBAR PRINCIPAL ================= -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom px-3 py-2">
        <div class="container-fluid">

            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="#">&lt;/Código42&gt;</a>

            <!-- Busca -->
            <form class="d-none d-md-flex mx-auto" style="max-width: 320px; width: 100%;" role="search">
                <div class="input-group">
                    <input
                        type="search"
                        class="form-control"
                        placeholder="Search"
                        aria-label="Buscar produtos"
                    >
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>

            <!-- Links e ícones -->
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-decoration-none text-dark d-none d-md-inline">Sobre</a>

                <a href="#" class="text-dark fs-5" title="Minha conta">
                    <i class="bi bi-person"></i>
                </a>

                <a href="#" class="text-dark fs-5 position-relative" title="Carrinho">
                    <i class="bi bi-cart"></i>
                </a>
            </div>

        </div>
    </nav>

    <!-- ================= FAIXA DE CATEGORIAS ================= -->
    <nav class="d-flex" aria-label="Categorias de produtos">
        <?php foreach ($categorias as $categoria): ?>
            <a
                href="<?php echo htmlspecialchars($categoria['href']); ?>"
                class="flex-fill text-center text-decoration-none text-white fw-bold py-2 categoria-link"
                style="background-color: <?php echo htmlspecialchars($categoria['cor']); ?>;"
            >
                <?php echo htmlspecialchars($categoria['nome']); ?>
            </a>
        <?php endforeach; ?>
    </nav>

</header>