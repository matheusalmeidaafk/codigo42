const AUTH_TOKEN_KEY = "jwtToken";

function salvarToken(token) {
  if (!token) {
    throw new Error("Token de autenticação não informado.");
  }

  localStorage.setItem(AUTH_TOKEN_KEY, token);
}

function obterToken() {
  return localStorage.getItem(AUTH_TOKEN_KEY);
}

function removerToken() {
  localStorage.removeItem(AUTH_TOKEN_KEY);
}

function estaAutenticado() {
  return obterToken() !== null;
}
