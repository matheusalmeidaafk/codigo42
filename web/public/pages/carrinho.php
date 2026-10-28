<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Meu carrinho | Código 42</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css"><link rel="stylesheet" href="/assets/css/design.css">
</head><body class="pagina-carrinho">
<?php require __DIR__ . '/../../components/header.php'; ?>
<main class="container py-5"><h1 class="mb-4">Meu carrinho</h1>
<div id="carrinhoPagina" class="card shadow-sm p-3 p-md-4" aria-live="polite"></div>
<div class="d-flex flex-wrap gap-3 mt-4"><a href="/pages/home.php" class="btn btn-dark">Continuar comprando</a>
<button id="limparCarrinho" class="btn btn-outline-danger" type="button">Esvaziar carrinho</button></div>
<p class="text-muted mt-3">Frete e pagamento ainda não estão disponíveis. Nenhum pedido será enviado por esta página.</p>
</main>
<?php require __DIR__ . '/../../components/footerHome.php'; ?>
</body></html>
