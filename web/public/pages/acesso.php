<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Entrar ou cadastrar | Código 42</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css"><link rel="stylesheet" href="/assets/css/design.css">
</head><body class="pagina-acesso">
<?php require __DIR__ . '/../../components/header.php'; ?>
<main class="acesso-main"><div class="acesso-shell" id="acessoShell">
<section class="acesso-formulario">
<div id="alertaAcesso" class="alert d-none" role="alert"></div>
<form id="formEntrar">
<h1>Fazer<br>login</h1><p>Acesse sua conta da &lt;/Código42&gt;.</p>
<label class="form-label">E-mail<input name="email" type="email" autocomplete="email" placeholder="Seu@email.com" required class="form-control"></label>
<label class="form-label">Senha<span class="campo-senha"><input name="senha" type="password" autocomplete="current-password" placeholder="Sua senha" required class="form-control"><button type="button" class="ver-senha" aria-label="Mostrar senha">◉</button></span></label>
<button class="btn btn-success acesso-submit" type="submit">Entrar</button>
</form>
<form id="formCadastro" class="d-none">
<h1>Criar<br>conta</h1><p>Junte-se à comunidade &lt;/Código42&gt;.</p>
<div class="cadastro-campos">
<label class="form-label">Nome completo<input name="nomeCompleto" autocomplete="name" placeholder="Seu nome" required minlength="2" class="form-control"></label>
<label class="form-label">E-mail<input name="email" type="email" autocomplete="email" placeholder="Seu@email.com" required class="form-control"></label>
<label class="form-label">Telefone<input name="telefone" type="tel" autocomplete="tel" placeholder="(99) 99999-9999" required class="form-control"></label>
<label class="form-label">CPF<input name="cpf" inputmode="numeric" placeholder="999.999.999-99" required class="form-control"></label>
<label class="form-label">Senha<span class="campo-senha"><input name="senha" type="password" autocomplete="new-password" minlength="8" placeholder="Sua senha" required class="form-control"><button type="button" class="ver-senha" aria-label="Mostrar senha">◉</button></span></label>
<label class="form-label">Confirmar senha<span class="campo-senha"><input name="confirmarSenha" type="password" autocomplete="new-password" minlength="8" placeholder="Repita a senha" required class="form-control"><button type="button" class="ver-senha" aria-label="Mostrar senha">◉</button></span></label>
</div><button class="btn btn-success acesso-submit" type="submit">Criar conta</button>
</form>
<a class="acesso-admin" href="/pages/admin-login.php">Acesso para administradores</a>
</section>
<section class="acesso-convite">
<div class="convite-login"><p>Novo por aqui?</p><h2>Junte-se<br>aos nerds.</h2><p>Crie sua conta e acesse produtos<br>exclusivos e ofertas especiais.</p><button id="abaCadastro" class="btn btn-outline-dark" type="button">Não tem conta? Cadastre-se</button></div>
<div class="convite-cadastro"><p>Já faz parte?</p><h2>Bem-vindo<br>de volta.</h2><p>Entre na sua conta e continue<br>explorando nossa coleção.</p><button id="abaEntrar" class="btn btn-outline-dark" type="button">Já tem conta? Login</button></div>
<span class="acesso-marca" aria-hidden="true">&lt;/Código42&gt;</span>
</section></div></main>
<?php require __DIR__ . '/../../components/footerHome.php'; ?>
<script src="/assets/js/acesso.js" defer></script></body></html>
