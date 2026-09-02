document.addEventListener("DOMContentLoaded", () => {
  inicializarAlternanciaAuth();
  inicializarPasswordToggle();
  inicializarLogin();
  inicializarCadastro();
  inicializarMascaras();
});

function inicializarAlternanciaAuth() {
  const authCard = document.querySelector("#authCard");
  const loginBanner = document.querySelector("#loginBanner");
  const cadastroBanner = document.querySelector("#cadastroBanner");
  const btnIrCadastro = document.querySelector("#btnIrCadastro");
  const btnIrLogin = document.querySelector("#btnIrLogin");

  if (
    !authCard ||
    !loginBanner ||
    !cadastroBanner ||
    !btnIrCadastro ||
    !btnIrLogin
  ) {
    return;
  }

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

function inicializarPasswordToggle() {
  const botoes = document.querySelectorAll(".password-toggle");

  botoes.forEach((botao) => {
    botao.addEventListener("click", () => {
      const targetId = botao.dataset.passwordTarget;
      const input = document.getElementById(targetId);

      if (!input) {
        return;
      }

      const eye = botao.querySelector(".password-icon-eye");
      const eyeSlash = botao.querySelector(".password-icon-eye-slash");

      const senhaVisivel = input.type === "text";

      if (senhaVisivel) {
        input.type = "password";

        eye?.classList.remove("d-none");
        eyeSlash?.classList.add("d-none");

        botao.setAttribute("aria-label", "Mostrar senha");
        botao.setAttribute("aria-pressed", "false");
      } else {
        input.type = "text";

        eye?.classList.add("d-none");
        eyeSlash?.classList.remove("d-none");

        botao.setAttribute("aria-label", "Ocultar senha");
        botao.setAttribute("aria-pressed", "true");
      }
    });
  });
}

function inicializarLogin() {
  const form = document.querySelector("#formLogin");
  const btnLogin = document.querySelector("#btnLogin");

  if (!form) {
    return;
  }

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const email = document.querySelector("#loginEmail")?.value.trim();

    const senha = document.querySelector("#loginSenha")?.value;

    if (!email || !senha) {
      mostrarMensagemAuth(
        "loginMensagem",
        "Preencha todos os campos.",
        "error",
      );

      return;
    }

    limparMensagemAuth("loginMensagem");

    if (btnLogin) {
      btnLogin.disabled = true;
      btnLogin.textContent = "Entrando...";
    }

    try {
      await login(email, senha);

      mostrarMensagemAuth(
        "loginMensagem",
        "Login realizado com sucesso.",
        "success",
      );

      setTimeout(() => {
        window.location.href = "../index.php";
      }, 800);
    } catch (error) {
      mostrarMensagemAuth("loginMensagem", error.message, "error");
    } finally {
      if (btnLogin) {
        btnLogin.disabled = false;
        btnLogin.textContent = "Entrar";
      }
    }
  });
}

function inicializarCadastro() {
  const form = document.querySelector("#formCadastro");
  const btnCadastro = document.querySelector("#btnCadastro");

  if (!form) {
    return;
  }

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const nomeCompleto = document.querySelector("#nomeCompleto")?.value.trim();

    const email = document.querySelector("#cadastroEmail")?.value.trim();

    const telefone = document
      .querySelector("#telefone")
      ?.value.replace(/\D/g, "");

    const cpf = document.querySelector("#cpf")?.value.replace(/\D/g, "");

    const senha = document.querySelector("#cadastroSenha")?.value;

    const confirmarSenha = document.querySelector("#confirmarSenha")?.value;

    if (
      !nomeCompleto ||
      !email ||
      !telefone ||
      !cpf ||
      !senha ||
      !confirmarSenha
    ) {
      mostrarMensagemAuth(
        "cadastroMensagem",
        "Preencha todos os campos obrigatórios.",
        "error",
      );

      return;
    }

    if (senha !== confirmarSenha) {
      mostrarMensagemAuth(
        "cadastroMensagem",
        "As senhas não coincidem.",
        "error",
      );

      return;
    }

    if (cpf.length !== 11) {
      mostrarMensagemAuth(
        "cadastroMensagem",
        "Informe um CPF válido.",
        "error",
      );

      return;
    }

    if (telefone.length !== 10 && telefone.length !== 11) {
      mostrarMensagemAuth(
        "cadastroMensagem",
        "Informe um telefone válido.",
        "error",
      );

      return;
    }

    const usuario = {
      imgperfilurl: null,
      nomeCompleto,
      email,
      telefone,
      cpf,
      senha,
    };

    limparMensagemAuth("cadastroMensagem");

    if (btnCadastro) {
      btnCadastro.disabled = true;
      btnCadastro.textContent = "Criando...";
    }

    try {
      await cadastrarUsuario(usuario);

      mostrarMensagemAuth(
        "cadastroMensagem",
        "Cadastro realizado com sucesso.",
        "success",
      );

      form.reset();

      setTimeout(() => {
        voltarParaLogin();
      }, 900);
    } catch (error) {
      mostrarMensagemAuth("cadastroMensagem", error.message, "error");
    } finally {
      if (btnCadastro) {
        btnCadastro.disabled = false;
        btnCadastro.textContent = "Criar conta";
      }
    }
  });
}

function voltarParaLogin() {
  const authCard = document.querySelector("#authCard");
  const loginBanner = document.querySelector("#loginBanner");
  const cadastroBanner = document.querySelector("#cadastroBanner");

  authCard?.classList.remove("register-mode");

  if (loginBanner) {
    loginBanner.hidden = false;
  }

  if (cadastroBanner) {
    cadastroBanner.hidden = true;
  }
}

function mostrarMensagemAuth(id, mensagem, tipo) {
  const container = document.getElementById(id);

  if (!container) {
    return;
  }

  container.textContent = mensagem;

  container.classList.remove("success", "error");

  container.classList.add(tipo);
}

function limparMensagemAuth(id) {
  const container = document.getElementById(id);

  if (!container) {
    return;
  }

  container.textContent = "";

  container.classList.remove("success", "error");
}

function limparMensagensAuth() {
  limparMensagemAuth("loginMensagem");
  limparMensagemAuth("cadastroMensagem");
}

function inicializarMascaras() {
  const telefone = document.querySelector("#telefone");
  const cpf = document.querySelector("#cpf");

  telefone?.addEventListener("input", (event) => {
    event.target.value = formatarTelefone(event.target.value);
  });

  cpf?.addEventListener("input", (event) => {
    event.target.value = formatarCpf(event.target.value);
  });
}

function formatarTelefone(valor) {
  const numeros = valor.replace(/\D/g, "").slice(0, 11);

  if (numeros.length <= 2) {
    return numeros;
  }

  if (numeros.length <= 6) {
    return numeros.replace(/^(\d{2})(\d+)/, "($1) $2");
  }

  if (numeros.length <= 10) {
    return numeros.replace(/^(\d{2})(\d{4})(\d+)/, "($1) $2-$3");
  }

  return numeros.replace(/^(\d{2})(\d{5})(\d+)/, "($1) $2-$3");
}

function formatarCpf(valor) {
  const numeros = valor.replace(/\D/g, "").slice(0, 11);

  return numeros
    .replace(/^(\d{3})(\d)/, "$1.$2")
    .replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3")
    .replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, "$1.$2.$3-$4");
}
