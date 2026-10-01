<?php

/**
 * Componente de busca.
 *
 * Apenas apresenta o formulário. Não contém regra de negócio:
 * ao enviar, a página de destino recebe o termo em ?q=
 *
 * Uso:
 *   <?php require_once __DIR__ . '/searchBar.php'; ?>
 *   <?php renderSearchBar(); ?>
 */
function renderSearchBar(string $termoAtual = ''): void
{
    $actionBusca = '/pages/produtos.php';
    $termoBusca = htmlspecialchars($termoAtual, ENT_QUOTES, 'UTF-8');
?>

    <form class="d-flex mx-auto" role="search" method="get" action="<?= $actionBusca ?>">

        <input
            class="form-control me-2 bg-dark text-light border-secondary"
            type="search"
            name="q"
            value="<?= $termoBusca ?>"
            placeholder="Search"
            aria-label="Search"
            maxlength="100"
            required>

        <button class="btn btn-outline-success" type="submit">
            Search
        </button>

    </form>

<?php
}