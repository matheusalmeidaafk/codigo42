async function apiRequest(endpoint, options = {}) {
  const token = obterToken();

  const headers = {
    "Content-Type": "application/json",
    ...(options.headers || {}),
  };

  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  let response;

  try {
    response = await fetch(`${API_BASE_URL}${endpoint}`, {
      ...options,
      headers,
    });
  } catch (error) {
    throw new Error(
      "Nao foi possivel conectar no servidor. Verifique-se o backend esta disponivel.",
    );
  }

  const contentType = response.headers.get("content-type") || "";

  const data = contentType.includes("application/json")
    ? await response.json()
    : null;

  if (!response.ok) {
    throw new Error(
      data?.erro ||
        `Erro na comunicacaoo com a API. Codigo: ${response.status}.`,
    );
  }

  return data;
}
