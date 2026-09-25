<?php

function requisitarApi(string $rota): array
{
    $apiBaseUrl = rtrim(
        getenv('API_URL') ?: 'http://app',
        '/'
    );

    $url = $apiBaseUrl . '/' . ltrim($rota, '/');

    $contexto = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 5,
            'ignore_errors' => true,
            'header' => "Accept: application/json\r\n",
        ],
    ]);

    $resposta = @file_get_contents(
        $url,
        false,
        $contexto
    );

    if ($resposta === false) {
        throw new RuntimeException(
            'Não foi possível acessar a API.'
        );
    }

    $statusCode = 0;

    if (
        isset($http_response_header[0])
        && preg_match(
            '#HTTP/\S+\s+(\d{3})#',
            $http_response_header[0],
            $matches
        )
    ) {
        $statusCode = (int) $matches[1];
    }

    $dados = json_decode($resposta, true);

    if ($statusCode < 200 || $statusCode >= 300) {
        $mensagem = is_array($dados)
            ? ($dados['erro'] ?? 'Erro retornado pela API.')
            : 'Erro retornado pela API.';

        throw new RuntimeException($mensagem);
    }

    if (!is_array($dados)) {
        throw new RuntimeException(
            'A API retornou uma resposta inválida.'
        );
    }

    return $dados;
}

function buscarProdutosApi(?int $categoriaId = null): array
{
    $rota = '/produtos';

    if ($categoriaId !== null) {
        $rota .= '?categoriaId=' . $categoriaId;
    }

    return requisitarApi($rota);
}

function buscarCategoriasApi(): array
{
    return requisitarApi('/categorias');
}
