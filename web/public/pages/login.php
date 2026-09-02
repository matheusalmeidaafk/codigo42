<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Codigo42</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body class="auth-body">

    <main class="auth-main d-flex align-items-center justify-content-center py-5 px-3">

        <section
            id="authCard"
            class="auth-card container-fluid p-0 overflow-hidden rounded-4"
            style="max-width: 1000px;"
            aria-label="Autenticação">

            <div class="row g-0">

                <!-- LOGIN -->
                <section class="auth-panel form-panel login-panel col-12 col-md-6 p-4 p-lg-5">
                    <div class="form-inner w-100 mx-auto" style="max-width: 350px;">

                        <div class="auth-title mb-3">
                            <h1 class="mb-2">Fazer<br>Login</h1>
                            <p class="mb-0">
                                Acesse sua conta da &lt;/Código42&gt;.
                            </p>
                        </div>

                        <form id="formLogin" class="auth-form">

                            <div class="mb-2">
                                <label for="loginEmail" class="form-label fw-bold mb-1">
                                    E-mail
                                </label>
                                <input
                                    type="email"
                                    id="loginEmail"
                                    class="form-control codigo42-input"
                                    placeholder="Seu@email.com"
                                    autocomplete="email"
                                    required>
                            </div>

                            <div class="mb-1">
                                <label for="loginSenha" class="form-label fw-bold mb-1">
                                    Senha
                                </label>

                                <div class="input-group">
                                    <input
                                        type="password"
                                        id="loginSenha"
                                        class="form-control codigo42-input"
                                        placeholder="*******"
                                        autocomplete="current-password"
                                        required>

                                    <button
                                        type="button"
                                        class="btn btn-dark password-toggle"
                                        data-password-target="loginSenha"
                                        aria-label="Mostrar senha">
                                        ◉
                                    </button>
                                </div>
                            </div>

                            <a href="#" class="d-block text-end text-dark fw-bold small text-decoration-none mb-3">
                                Esqueceu a senha?
                            </a>

                            <button
                                type="submit"
                                id="btnLogin"
                                class="auth-button btn btn-success d-block mx-auto px-5">
                                Entrar
                            </button>

                            <p id="loginMensagem"
                                class="auth-message text-center mt-3 mb-0"
                                role="alert"
                                aria-live="polite"></p>

                        </form>
                    </div>
                </section>

                <!-- CADASTRO -->
                <section class="auth-panel form-panel register-panel col-12 col-md-6 p-4 p-lg-5">
                    <div class="form-inner w-100 mx-auto" style="max-width: 450px;">

                        <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                            <div class="auth-title">
                                <h1 class="mb-1">Criar<br>Conta</h1>
                                <p class="mb-0">
                                    Junte-se à comunidade &lt;/Código42&gt;.
                                </p>
                            </div>

                            <div class="profile-upload flex-shrink-0 text-center">
                                <label
                                    for="imagemPerfil"
                                    class="profile-upload-circle rounded-circle d-flex align-items-center justify-content-center"
                                    title="Adicionar imagem">
                                    +
                                </label>

                                <input
                                    id="imagemPerfil"
                                    type="file"
                                    class="d-none"
                                    accept="image/png, image/jpeg, image/webp">
                            </div>
                        </div>

                        <form id="formCadastro" class="auth-form">

                            <div class="row g-2">

                                <div class="col-12 col-sm-6">
                                    <label for="nomeCompleto" class="form-label fw-bold mb-1">
                                        Nome completo
                                    </label>
                                    <input
                                        type="text"
                                        id="nomeCompleto"
                                        class="form-control codigo42-input"
                                        placeholder="Seu nome"
                                        autocomplete="name"
                                        required>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="cadastroEmail" class="form-label fw-bold mb-1">
                                        E-mail
                                    </label>
                                    <input
                                        type="email"
                                        id="cadastroEmail"
                                        class="form-control codigo42-input"
                                        placeholder="Seu@email.com"
                                        autocomplete="email"
                                        required>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="telefone" class="form-label fw-bold mb-1">
                                        Telefone
                                    </label>
                                    <input
                                        type="tel"
                                        id="telefone"
                                        class="form-control codigo42-input"
                                        placeholder="(99) 9999-9999"
                                        inputmode="numeric"
                                        autocomplete="tel"
                                        required>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="cpf" class="form-label fw-bold mb-1">
                                        CPF
                                    </label>
                                    <input
                                        type="text"
                                        id="cpf"
                                        class="form-control codigo42-input"
                                        placeholder="999.999.999-99"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        required>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="cadastroSenha" class="form-label fw-bold mb-1">
                                        Senha
                                    </label>

                                    <div class="input-group">
                                        <input
                                            type="password"
                                            id="cadastroSenha"
                                            class="form-control codigo42-input"
                                            placeholder="Sua senha"
                                            autocomplete="new-password"
                                            required>

                                        <button
                                            type="button"
                                            class="btn btn-dark password-toggle"
                                            data-password-target="cadastroSenha"
                                            aria-label="Mostrar senha">
                                            ◉
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="confirmarSenha" class="form-label fw-bold mb-1">
                                        Confirmar senha
                                    </label>

                                    <div class="input-group">
                                        <input
                                            type="password"
                                            id="confirmarSenha"
                                            class="form-control codigo42-input"
                                            placeholder="Repita senha"
                                            autocomplete="new-password"
                                            required>

                                        <button
                                            type="button"
                                            class="btn btn-dark password-toggle"
                                            data-password-target="confirmarSenha"
                                            aria-label="Mostrar senha">
                                            ◉
                                        </button>
                                    </div>
                                </div>

                            </div>

                            <button
                                type="submit"
                                id="btnCadastro"
                                class="auth-button btn btn-success d-block ms-auto mt-3 px-4">
                                Criar conta
                            </button>

                            <p id="cadastroMensagem"
                                class="auth-message text-center mt-2 mb-0"
                                role="alert"
                                aria-live="polite"></p>

                        </form>
                    </div>
                </section>

                <!-- BANNER -->
                <aside class="auth-panel banner-panel col-12 col-md-6 d-flex align-items-center justify-content-center text-center p-4">

                    <div class="banner-content position-relative z-1">

                        <div class="banner-line mx-auto mb-3"></div>

                        <div id="loginBanner">
                            <p>Novo por aqui?</p>

                            <h2 class="mb-3">Junte-se<br>aos nerds.</h2>

                            <p class="banner-description mx-auto mb-3">
                                Crie sua conta e acesse produtos exclusivos e ofertas especiais.
                            </p>

                            <button
                                type="button"
                                id="btnIrCadastro"
                                class="banner-button btn">
                                NÃO TEM CONTA? CADASTRE-SE
                            </button>
                        </div>

                        <div id="cadastroBanner" hidden>
                            <p>Já faz parte?</p>

                            <h2 class="mb-3">Bem vindo<br>de volta</h2>

                            <p class="banner-description mx-auto mb-3">
                                Entre na sua conta e continue explorando nossa coleção.
                            </p>

                            <button
                                type="button"
                                id="btnIrLogin"
                                class="banner-button btn">
                                JÁ TEM CONTA? LOGIN
                            </button>
                        </div>

                        <div class="banner-line mx-auto mt-3"></div>

                    </div>

                    <div class="banner-watermark position-absolute bottom-0 start-50 translate-middle-x">
                        &lt;/Código42&gt;
                    </div>

                </aside>

            </div>
        </section>

    </main>


    <script src="../assets/js/config/apiConfig.js"></script>
    <script src="../assets/js/utils/authStorage.js"></script>
    <script src="../assets/js/services/apiService.js"></script>
    <script src="../assets/js/services/authService.js"></script>
    <script src="../assets/js/main.js"></script>

</body>

</html>