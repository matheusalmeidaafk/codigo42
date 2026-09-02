<?php

require_once __DIR__ . '/../../components/inputText.php';
require_once __DIR__ . '/../../components/inputPassword.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Código42</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

    <link
        rel="stylesheet"
        href="../assets/css/login.css">
</head>

<body class="auth-body">

    <main class="auth-main d-flex align-items-center justify-content-center py-5 px-3">

        <section
            id="authCard"
            class="auth-card container-fluid p-0 overflow-hidden rounded-4"
            style="max-width: 1000px;"
            aria-label="Autenticação">

            <section class="auth-form-panel login-panel p-4 p-lg-5">

                <div
                    class="form-inner w-100 mx-auto"
                    style="max-width: 350px;">

                    <div class="auth-title mb-3">

                        <h1 class="mb-2">
                            Fazer<br>
                            Login
                        </h1>

                        <p class="mb-0">
                            Acesse sua conta da &lt;/Código42&gt;.
                        </p>

                    </div>

                    <form
                        id="formLogin"
                        class="auth-form">

                        <?php
                        renderInputText([
                            'id' => 'loginEmail',
                            'label' => 'E-mail',
                            'type' => 'email',
                            'placeholder' => 'Seu@email.com',
                            'autocomplete' => 'email',
                            'required' => true,
                            'wrapperClass' => 'mb-2'
                        ]);
                        ?>

                        <?php
                        renderInputPassword([
                            'id' => 'loginSenha',
                            'label' => 'Senha',
                            'placeholder' => '*******',
                            'autocomplete' => 'current-password',
                            'required' => true,
                            'wrapperClass' => 'mb-1'
                        ]);
                        ?>

                        <a
                            href="#"
                            class="d-block text-end text-dark fw-bold small text-decoration-none mb-3">
                            Esqueceu a senha?
                        </a>

                        <button
                            type="submit"
                            id="btnLogin"
                            class="auth-button btn btn-success d-block mx-auto px-5">
                            Entrar
                        </button>

                        <p
                            id="loginMensagem"
                            class="auth-message text-center mt-3 mb-0"
                            role="alert"
                            aria-live="polite"></p>

                    </form>

                </div>

            </section>

            <section class="auth-form-panel register-panel p-4 p-lg-5">

                <div
                    class="form-inner w-100 mx-auto"
                    style="max-width: 450px;">

                    <div class="d-flex align-items-start justify-content-between gap-3 mb-2">

                        <div class="auth-title">

                            <h1 class="mb-1">
                                Criar<br>
                                Conta
                            </h1>

                            <p class="mb-0">
                                Junte-se à comunidade &lt;/Código42&gt;.
                            </p>

                        </div>

                        <div class="profile-upload flex-shrink-0 text-center">

                            <label
                                for="imagemPerfil"
                                class="profile-upload-circle rounded-circle d-flex align-items-center justify-content-center"
                                title="Adicionar imagem">

                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                </svg>

                            </label>

                            <input
                                id="imagemPerfil"
                                type="file"
                                class="d-none"
                                accept="image/png, image/jpeg, image/webp">

                        </div>

                    </div>

                    <form
                        id="formCadastro"
                        class="auth-form">

                        <div class="row g-2">

                            <?php
                            renderInputText([
                                'id' => 'nomeCompleto',
                                'label' => 'Nome completo',
                                'placeholder' => 'Seu nome',
                                'autocomplete' => 'name',
                                'required' => true,
                                'wrapperClass' => 'col-12 col-sm-6'
                            ]);
                            ?>

                            <?php
                            renderInputText([
                                'id' => 'cadastroEmail',
                                'label' => 'E-mail',
                                'type' => 'email',
                                'placeholder' => 'Seu@email.com',
                                'autocomplete' => 'email',
                                'required' => true,
                                'wrapperClass' => 'col-12 col-sm-6'
                            ]);
                            ?>

                            <?php
                            renderInputText([
                                'id' => 'telefone',
                                'label' => 'Telefone',
                                'type' => 'tel',
                                'placeholder' => '(99) 99999-9999',
                                'autocomplete' => 'tel',
                                'inputmode' => 'numeric',
                                'required' => true,
                                'wrapperClass' => 'col-12 col-sm-6'
                            ]);
                            ?>

                            <?php
                            renderInputText([
                                'id' => 'cpf',
                                'label' => 'CPF',
                                'placeholder' => '999.999.999-99',
                                'autocomplete' => 'off',
                                'inputmode' => 'numeric',
                                'required' => true,
                                'wrapperClass' => 'col-12 col-sm-6'
                            ]);
                            ?>

                            <?php
                            renderInputPassword([
                                'id' => 'cadastroSenha',
                                'label' => 'Senha',
                                'placeholder' => 'Sua senha',
                                'autocomplete' => 'new-password',
                                'required' => true,
                                'wrapperClass' => 'col-12 col-sm-6'
                            ]);
                            ?>

                            <?php
                            renderInputPassword([
                                'id' => 'confirmarSenha',
                                'label' => 'Confirmar senha',
                                'placeholder' => 'Repita senha',
                                'autocomplete' => 'new-password',
                                'required' => true,
                                'wrapperClass' => 'col-12 col-sm-6'
                            ]);
                            ?>

                        </div>

                        <button
                            type="submit"
                            id="btnCadastro"
                            class="auth-button btn btn-success d-block ms-auto mt-3 px-4">
                            Criar conta
                        </button>

                        <p
                            id="cadastroMensagem"
                            class="auth-message text-center mt-2 mb-0"
                            role="alert"
                            aria-live="polite"></p>

                    </form>

                </div>

            </section>

            <aside class="banner-panel p-4">

                <div class="banner-content position-relative z-1">

                    <div class="banner-line mx-auto mb-3"></div>

                    <div id="loginBanner">

                        <p>Novo por aqui?</p>

                        <h2 class="mb-3">
                            Junte-se<br>
                            aos nerds.
                        </h2>

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

                    <div
                        id="cadastroBanner"
                        hidden>

                        <p>Já faz parte?</p>

                        <h2 class="mb-3">
                            Bem vindo<br>
                            de volta
                        </h2>

                        <p class="banner-description mx-auto mb-3">
                            Entre na sua conta e continue explorando a &lt;/Código42&gt;.
                        </p>

                        <button
                            type="button"
                            id="btnIrLogin"
                            class="banner-button btn">
                            JÁ TEM CONTA? ENTRE
                        </button>

                    </div>

                </div>

            </aside>

        </section>

    </main>

    <script src="../assets/js/config/apiConfig.js"></script>
    <script src="../assets/js/utils/authStorage.js"></script>
    <script src="../assets/js/services/apiService.js"></script>
    <script src="../assets/js/services/authService.js"></script>
    <script src="../assets/js/main.js"></script>

</body>

</html>