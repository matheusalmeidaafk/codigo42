<?php
require_once __DIR__ . '/../../services/produtoApi.php';
require_once __DIR__ . '/../../components/cardProduto.php';
$produtos = []; $erro = null; $htmlFiltros = '';
ob_start();
try { require __DIR__ . '/../../components/filtrosProdutos.php'; $htmlFiltros = ob_get_contents(); }
catch (Throwable $e) { $erro = 'Não foi possível carregar os produtos agora. Tente novamente em instantes.'; error_log($e->getMessage()); }
finally { ob_end_clean(); }
$q = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';
$ordem = is_string($_GET['ordem'] ?? null) ? $_GET['ordem'] : 'novidades';
$limite = in_array((int) ($_GET['limite'] ?? 50), [50, 70, 100], true) ? (int) ($_GET['limite'] ?? 50) : 50;
// Uma entrada por produto, mesmo quando a API retorna cores/imagens diferentes.
$unicos = [];
foreach ($produtos as $p) {
    if ($q !== '' && preg_match('/' . preg_quote($q, '/') . '/iu', $p['nome'] . ' ' . ($p['descricao'] ?? '')) !== 1) continue;
    $unicos[$p['id_produto']] ??= $p;
}
$produtos = array_values($unicos);
usort($produtos, static function ($a, $b) use ($ordem) {
    $precoA = (float) ($a['preco_final'] ?? $a['preco']); $precoB = (float) ($b['preco_final'] ?? $b['preco']);
    return match ($ordem) {
        'preco-asc' => $precoA <=> $precoB,
        'preco-desc' => $precoB <=> $precoA,
        'avaliacoes' => (float) ($b['estrelas'] ?? 0) <=> (float) ($a['estrelas'] ?? 0),
        default => (int) $b['id_produto'] <=> (int) $a['id_produto'],
    };
});
$total = count($produtos); $paginas = max(1, (int) ceil($total / $limite));
$pagina = max(1, min($paginas, (int) ($_GET['pagina'] ?? 1)));
$produtos = array_slice($produtos, ($pagina - 1) * $limite, $limite);
$escape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$linkPagina = static fn($n) => '?' . http_build_query(array_merge($_GET, ['pagina' => $n]));
?>
<!doctype html><html lang="pt-BR"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Produtos | Código 42</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/filtros.css"><link rel="stylesheet" href="/assets/css/design.css">
<link rel="stylesheet" href="/assets/css/style.css"><link rel="stylesheet" href="/assets/css/cardProduto.css">
</head><body class="pagina-produtos">
<?php require __DIR__ . '/../../components/header.php'; ?>
<main class="catalogo">
<nav class="caminho" aria-label="Navegação"><a href="/pages/home.php">Home</a> / Produtos</nav>
<h1 class="visually-hidden">Produtos</h1>
<div class="catalogo-layout">
<aside class="catalogo-filtros" aria-label="Filtros de produtos"><?= $htmlFiltros ?></aside>
<section aria-label="Resultados" class="catalogo-resultados">
<form class="catalogo-ordenacao" method="get">
<?php foreach (['q','categorias','cor','tamanho','precoMin','precoMax'] as $chave): if (isset($_GET[$chave]) && is_string($_GET[$chave])): ?>
<input type="hidden" name="<?= $chave ?>" value="<?= $escape($_GET[$chave]) ?>">
<?php endif; endforeach; ?>
<label>↕ Ordenar: <select name="ordem" class="form-select">
<?php foreach (['novidades'=>'Novidades','avaliacoes'=>'Melhor avaliados','preco-asc'=>'Preço crescente','preco-desc'=>'Preço decrescente'] as $valor=>$rotulo): ?>
<option value="<?= $valor ?>" <?= $ordem === $valor ? 'selected' : '' ?>><?= $rotulo ?></option><?php endforeach; ?>
</select></label>
<label>Exibir: <select name="limite" class="form-select"><?php foreach ([50,70,100] as $n): ?><option value="<?= $n ?>" <?= $limite === $n ? 'selected' : '' ?>><?= $n ?> por página</option><?php endforeach; ?></select></label>
<button class="btn btn-outline-dark" type="submit">Aplicar</button>
</form>
<p class="text-secondary" role="status"><?= $total ?> produto(s)<?= $q !== '' ? ' para “' . $escape($q) . '”' : '' ?></p>
<?php if ($erro): ?><div class="alert alert-danger" role="alert"><?= $escape($erro) ?></div>
<?php elseif (!$produtos): ?><p class="alert alert-secondary">Nenhum produto encontrado. Tente ajustar os filtros.</p><?php endif; ?>
<div class="catalogo-grid">
<?php foreach ($produtos as $p): CardProduto((int) $p['id_produto'], $p['nome'], (float) ($p['estrelas'] ?? 0), (float) $p['preco'], (float) ($p['preco_final'] ?? $p['preco']), isset($p['porcentagem_desconto']) ? (float) $p['porcentagem_desconto'] : null, $p['variacao_imagem_url'] ?? $p['imagem_url'] ?? '', $p['tamanhos'] ?? []); endforeach; ?>
</div>
<?php if ($paginas > 1): ?><nav aria-label="Páginas de produtos" class="catalogo-paginacao">
<?php if ($pagina > 1): ?><a href="<?= $escape($linkPagina($pagina-1)) ?>" aria-label="Página anterior">«</a><?php endif; ?>
<?php for ($n = max(1,$pagina-2); $n <= min($paginas,$pagina+2); $n++): ?><a href="<?= $escape($linkPagina($n)) ?>" <?= $n === $pagina ? 'aria-current="page"' : '' ?>><?= $n ?></a><?php endfor; ?>
<?php if ($pagina < $paginas): ?><a href="<?= $escape($linkPagina($pagina+1)) ?>" aria-label="Próxima página">»</a><?php endif; ?>
</nav><?php endif; ?>
</section></div></main>
<?php require __DIR__ . '/../../components/footerHome.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
