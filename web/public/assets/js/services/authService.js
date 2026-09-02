async function login(email, senha) {
  const data = await apiRequest(API_ENDPOINTS.login, {
    method: "POST",
    body: JSON.stringify({ email, senha }),
  });

  if (!data?.token) {
    throw new Error("O backend não retornou um token de autenticação.");
  }

  salvarToken(data.token);

  return data;
}

async function cadastrarUsuario(usuario) {
  return await apiRequest(API_ENDPOINTS.cadastro, {
    method: "POST",
    body: JSON.stringify(usuario),
  });
}

function logout() {
  removerToken();
}

function usuarioEstaAutenticado() {
  return estaAutenticado();
}
