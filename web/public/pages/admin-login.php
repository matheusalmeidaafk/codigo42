<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Administração | Código 42</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css"></head>
<body class="bg-light"><main class="container min-vh-100 d-flex align-items-center justify-content-center">
<section class="card shadow-sm" style="width:100%;max-width:440px"><div class="card-body p-4 p-md-5">
<img src="/assets/images/logo.png" alt="Código 42" class="d-block mx-auto mb-4" style="max-width:170px">
<h1 class="h4 text-center mb-2">Acesso administrativo</h1><p class="text-center text-muted mb-4">Área restrita à equipe autorizada.</p>
<div class="alert alert-danger d-none" id="adminLoginErro" role="alert"></div>
<form id="adminLoginForm" class="vstack gap-3"><label>E-mail<input class="form-control mt-1" name="email" type="email" required autocomplete="username"></label>
<label>Senha<input class="form-control mt-1" name="senha" type="password" required autocomplete="current-password"></label>
<button type="submit" class="btn btn-dark">Entrar no painel</button></form>
<a href="/pages/home.php" class="d-block mt-4 text-center link-secondary">Voltar à loja</a>
</div></section></main><script src="/assets/js/auth.js"></script>
<script src="/assets/js/admin-login.js" defer></script></body></html>
