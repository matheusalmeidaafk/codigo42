const authTokenKey = "jwtToken";

function salvarToken(token) {
  if (!token) {
    throw new Error("Token de autenticacao nao informado.");
  }

  localStorage.setItem(authTokenKey, token);
}

function obterToken() {
  return localStorage.getItem(authTokenKey);
}

function removerToken() {
  localStorage.removeItem(authTokenKey);
}

function estaAutenticado() {
  return obterToken() !== null;
}
