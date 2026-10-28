<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Painel administrativo | Código 42</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css"></head>
<body class="bg-light"><header class="bg-dark text-white py-3"><div class="container d-flex justify-content-between align-items-center">
<h1 class="h4 mb-0">Código 42 <span class="fw-normal text-white-50">/ Administração</span></h1>
<div><a href="/pages/home.php" class="btn btn-sm btn-outline-light me-2">Ver loja</a><button id="adminSair" class="btn btn-sm btn-outline-light">Sair</button></div>
</div></header>
<main class="container py-5"><div id="adminAlerta" class="alert d-none" role="alert"></div>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"><div><h2 class="h3 mb-1">Gerenciar <span id="adminRecursoTitulo">produtos</span></h2>
<p class="text-muted mb-0">Cadastre produtos com categorias, preços e imagens em um só lugar.</p></div>
<button id="adminNovo" type="button" class="btn btn-dark">+ Novo registro</button></div>
<nav class="nav nav-pills gap-2 mb-4" aria-label="Recursos administrativos">
<button class="nav-link active" data-resource="produtos">Produtos</button>
<button class="nav-link" data-resource="categorias">Categorias</button>
<button class="nav-link" data-resource="banners">Banners</button>
<button class="nav-link" data-resource="usuarios">Usuários</button></nav>
<section class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover align-middle">
<thead id="adminTabelaCabecalho"></thead><tbody id="adminTabelaCorpo"></tbody></table></div></div></section>
</main>
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalTitulo" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-scrollable">
<div class="modal-content"><div class="modal-header"><h2 class="modal-title fs-5" id="adminModalTitulo">Registro</h2>
<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Fechar"></button></div>
<form id="adminForm"><div class="modal-body"><div id="adminFormAlerta" class="alert d-none" role="alert"></div><div id="adminFormCampos" class="row g-3"></div></div>
<div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-dark">Salvar</button></div></form></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/auth.js"></script>
<script src="/assets/js/admin.js?v=4" defer></script>

</body></html>
