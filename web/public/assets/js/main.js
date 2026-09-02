document.addEventListener("DOMContentLoaded", () => {
    inicializarAlternanciaAuth();
    inicializarLogin();
    inicializarCadastro();
});

function inicializarAlternanciaAuth() {
    const authCard = document.querySelector("#authCard");
    const loginBanner = document.querySelector("#loginBanner");
    const cadastroBanner = document.querySelector("#cadastroBanner");
    const btnIrCadastro = document.querySelector("#btnIrCadastro");
    const btnIrLogin = document.querySelector("#btnIrLogin");

    if (!authCard || !btnIrCadastro || !btnIrLogin) return;

    btnIrCadastro.addEventListener("click", () => {
        authCard.classList.add("register-mode");
        loginBanner.hidden = true;
        cadastroBanner.hidden = false;
        limparMensagensAuth();
    });

    btnIrLogin.addEventListener("click", () => {
        authCard.classList.remove("register-mode");
        loginBanner.hidden = false;
        cadastroBanner.hidden = true;
        limparMensagensAuth();
    });
}

function inicializarLogin() {
    const form = document.querySelector("#loginForm");

    if (!form) return;

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const email = document.querySelector("#loginEmail")?.value.trim();
        const senha = document.querySelector("#loginSenha")?.value;

        if (!email || !senha) {
            mostrarMensagemAuth("Preencha todos os campos.", "danger");
            return;
        }

        try {
            await login(email, senha);
            mostrarMensagemAuth("Login realizado com sucesso.", "success");

            setTimeout(() => {
                window.location.href = "index.php";
            }, 800);
        } catch (error) {
            mostrarMensagemAuth(error.message, "danger");
        }
    });
}

function inicializarCadastro() {
    const form = document.querySelector("#cadastroForm");

    if (!form) return;

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const usuario = {
            imgperfilurl: document.querySelector("#cadastroImgPerfilUrl")?.value.trim() || "",
            nomeCompleto: document.querySelector("#cadastroNome")?.value.trim(),
            email: document.querySelector("#cadastroEmail")?.value.trim(),
            telefone: document.querySelector("#cadastroTelefone")?.value.trim(),
            cpf: document.querySelector("#cadastroCpf")?.value.trim(),
            senha: document.querySelector("#cadastroSenha")?.value
        };

        if (
            !usuario.nomeCompleto ||
            !usuario.email ||
            !usuario.telefone ||
            !usuario.cpf ||
            !usuario.senha
        ) {
            mostrarMensagemAuth("Preencha todos os campos obrigatórios.", "danger");
            return;
        }

        try {
            await cadastrarUsuario(usuario);

            mostrarMensagemAuth(
                "Cadastro realizado com sucesso. Agora faça login.",
                "success"
            );

            form.reset();

            const authCard = document.querySelector("#authCard");
            const loginBanner = document.querySelector("#loginBanner");
            const cadastroBanner = document.querySelector("#cadastroBanner");

            authCard?.classList.remove("register-mode");

            if (loginBanner) loginBanner.hidden = false;
            if (cadastroBanner) cadastroBanner.hidden = true;
        } catch (error) {
            mostrarMensagemAuth(error.message, "danger");
        }
    });
}

function mostrarMensagemAuth(mensagem, tipo) {
    const container = document.querySelector("#authMessage");

    if (!container) return;

    container.className = `alert alert-${tipo}`;
    container.textContent = mensagem;
    container.hidden = false;
}

function limparMensagensAuth() {
    const container = document.querySelector("#authMessage");

    if (!container) return;

    container.hidden = true;
    container.textContent = "";
}