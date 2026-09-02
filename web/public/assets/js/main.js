document.addEventListener("DOMContentLoaded", () => {
  inicializarLogin();
  inicializarCadastro();
});

function inicializarLogin() {
  const formLogin = document.querySelector("#formLogin");

  if (!formLogin) {
    return;
  }

  formLogin.addEventListener("submit", async (event) => {
    event.preventDefault();

    const email = document.querySelector("#loginEmail").value.trim();
    const senha = document.querySelector("#loginSenha").value;

    limparMensagem("loginMensagem");

    if (!email || !senha) {
      exibirMensagem(
        "loginMensagem",
        "Informe seu e-mail e sua senha.",
        "danger",
      );
      return;
    }

    alterarEstadoBotao("btnLogin", true, "Entrando...");

    try {
      await login(email, senha);

      exibirMensagem(
        "loginMensagem",
        "Login realizado com sucesso!",
        "success",
      );

      setTimeout(() => {
        window.location.href = "../index.php";
      }, 700);
    } catch (error) {
      console.error(error);

      exibirMensagem("loginMensagem", error.message, "danger");
    } finally {
      alterarEstadoBotao("btnLogin", false, "Entrar");
    }
  });
}

function inicializarCadastro() {
  const formCadastro = document.querySelector("#formCadastro");

  if (!formCadastro) {
    return;
  }

  formCadastro.addEventListener("submit", async (event) => {
    event.preventDefault();

    limparMensagem("cadastroMensagem");

    const usuario = {
      imgperfilurl: null,
      nomeCompleto: document.querySelector("#nomeCompleto").value.trim(),
      email: document.querySelector("#cadastroEmail").value.trim(),
      telefone: document.querySelector("#telefone").value.trim(),
      cpf: document.querySelector("#cpf").value.trim(),
      senha: document.querySelector("#cadastroSenha").value,
    };

    const confirmarSenha = document.querySelector("#confirmarSenha").value;

    if (
      !usuario.nomeCompleto ||
      !usuario.email ||
      !usuario.telefone ||
      !usuario.cpf ||
      !usuario.senha
    ) {
      exibirMensagem(
        "cadastroMensagem",
        "Preencha todos os campos obrigatórios.",
        "danger",
      );
      return;
    }

    if (usuario.senha !== confirmarSenha) {
      exibirMensagem("cadastroMensagem", "As senhas não conferem.", "danger");
      return;
    }

    alterarEstadoBotao("btnCadastro", true, "Cadastrando...");

    try {
      await cadastrarUsuario(usuario);

      exibirMensagem(
        "cadastroMensagem",
        "Cadastro realizado com sucesso! Agora você pode entrar.",
        "success",
      );

      formCadastro.reset();
    } catch (error) {
      console.error(error);

      exibirMensagem("cadastroMensagem", error.message, "danger");
    } finally {
      alterarEstadoBotao("btnCadastro", false, "Cadastrar");
    }
  });
}

function exibirMensagem(elementId, mensagem, tipo) {
  const elemento = document.querySelector(`#${elementId}`);

  if (!elemento) {
    return;
  }

  elemento.className = `alert alert-${tipo}`;
  elemento.textContent = mensagem;
  elemento.classList.remove("d-none");
}

function limparMensagem(elementId) {
  const elemento = document.querySelector(`#${elementId}`);

  if (!elemento) {
    return;
  }

  elemento.className = "alert d-none";
  elemento.textContent = "";
}

function alterarEstadoBotao(buttonId, disabled, texto) {
  const button = document.querySelector(`#${buttonId}`);

  if (!button) {
    return;
  }

  button.disabled = disabled;
  button.textContent = texto;
}
