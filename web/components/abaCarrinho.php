<?php
/**
 * componentesCarrinho.php (versão Bootstrap)
 * ------------------------------------------------------------
 * Componente de FRONT-END responsável por renderizar um item
 * dentro do carrinho (imagem + nome + descrição + preços +
 * controles de quantidade + remover), usando classes do
 * Bootstrap 5 em vez de CSS customizado.
 *
 * IMPORTANTE:
 * - Este arquivo não busca nem recebe dados de back-end.
 * - A função abaixo tem valores padrão (placeholders) apenas
 *   para que o componente já "nasça" visualmente pronto.
 * - Requer que o Bootstrap 5 (CSS) esteja carregado na página
 *   que inclui este componente. Se for usar este arquivo
 *   sozinho, adicione o <link> do Bootstrap no <head> da página.
 * ------------------------------------------------------------
 */

/**
 * Renderiza (retorna em HTML) um item do carrinho.
 *
 * @param string $imagem         Caminho/URL da imagem do produto.
 * @param string $nome           Nome do produto.
 * @param string $descricao      Descrição curta do produto.
 * @param string $precoOriginal  Preço original (sem desconto), ex: "50,00".
 * @param string $precoDesconto  Preço com desconto aplicado, ex: "40,00".
 * @param int    $quantidade     Quantidade selecionada do item.
 * @param string $idItem         Identificador do item (usado nos botões, ex: data-id).
 *
 * @return string HTML do componente pronto para ser ecoado (echo).
 */
function renderItemCarrinho(
    string $imagem = 'https://via.placeholder.com/80',
    string $nome = 'Nome do Produto',
    string $descricao = 'Descrição do produto',
    string $precoOriginal = '0,00',
    string $precoDesconto = '0,00',
    int $quantidade = 1,
    string $idItem = ''
): string {
    ob_start();
    ?>
    <div class="d-flex gap-3 py-3 border-bottom item-carrinho" data-item-id="<?php echo htmlspecialchars($idItem); ?>">

        <!-- Imagem -->
        <div class="flex-shrink-0" style="width: 80px; height: 80px;">
            <img
                src="<?php echo htmlspecialchars($imagem); ?>"
                alt="<?php echo htmlspecialchars($nome); ?>"
                class="rounded w-100 h-100"
                style="object-fit: cover;"
            >
        </div>

        <!-- Informações -->
        <div class="flex-grow-1">
            <p class="fw-bold mb-0"><?php echo htmlspecialchars($nome); ?></p>
            <p class="text-muted small mb-2"><?php echo htmlspecialchars($descricao); ?></p>

            <div class="d-flex align-items-center gap-2 mb-2">
                <?php if ($precoOriginal !== $precoDesconto): ?>
                    <span class="text-muted small text-decoration-line-through">
                        R$ <?php echo htmlspecialchars($precoOriginal); ?>
                    </span>
                <?php endif; ?>
                <span class="fw-bold">
                    R$ <?php echo htmlspecialchars($precoDesconto); ?>
                </span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group" aria-label="Quantidade">
                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-menos"
                        data-item-id="<?php echo htmlspecialchars($idItem); ?>"
                    >-</button>
                    <button type="button" class="btn btn-outline-secondary disabled qtd-numero">
                        <?php echo (int) $quantidade; ?>
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-mais"
                        data-item-id="<?php echo htmlspecialchars($idItem); ?>"
                    >+</button>
                </div>

                <button
                    type="button"
                    class="btn btn-link text-danger ms-auto p-0 btn-remover"
                    data-item-id="<?php echo htmlspecialchars($idItem); ?>"
                    title="Remover item"
                >
                    <i class="bi bi-trash"></i>&#128465;
                </button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}