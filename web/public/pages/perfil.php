<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Minha conta | Código 42</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css"><link rel="stylesheet" href="/assets/css/produtoSugestoes.css"></head>
<body class="bg-light"><?php require __DIR__ . '/../../components/header.php'; ?>
<main class="container py-5"><div class="d-flex justify-content-between align-items-center mb-4"><h1>Minha conta</h1>
<button class="btn btn-outline-secondary" type="button" id="sairConta">Sair</button></div>
<div id="perfilAlerta" class="alert d-none" role="alert"></div>
<div class="row g-4"><div class="col-lg-5"><section class="card shadow-sm h-100"><div class="card-body p-4">
<h2 class="h5 mb-3">Meus dados</h2><form id="formPerfil" class="vstack gap-3">
<label>Nome completo<input class="form-control mt-1" name="nome_completo" required minlength="2"></label>
<label>E-mail<input class="form-control mt-1" name="email" type="email" required></label>
<label>Telefone<input class="form-control mt-1" name="telefone"></label>
<label>CPF<input class="form-control mt-1" id="perfilCpf" readonly></label>
<button class="btn btn-dark" type="submit">Salvar alterações</button></form></div></section></div>
<div class="col-lg-7"><section class="card shadow-sm mb-4"><div class="card-body p-4"><h2 class="h5">Meu carrinho</h2>
<div id="perfilCarrinho" class="vstack gap-2"></div><p class="fw-semibold mt-3" id="perfilTotal"></p></div></section>
<section class="card shadow-sm"><div class="card-body p-4"><h2 class="h5">Itens guardados</h2><div id="perfilFavoritos"></div></div></section></div></div>
<section id="produto-sugestoes" class="produto-sugestoes mt-5" aria-labelledby="tituloSugestoes">
<h2 id="tituloSugestoes" class="h4">Você também pode gostar</h2>
<p class="text-center text-secondary" data-sugestoes-status role="status">Carregando sugestões...</p>
<div data-sugestoes-renderizado></div></section></main>
<script src="/assets/js/carrosselResponsivo.js"></script>
<script src="/assets/js/produtoSugestoes.js"></script>
<script src="/assets/js/perfil.js" defer></script></body></html>
