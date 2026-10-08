
<?php

function requisitarApi(string $rota): array
{

    error_log($rota);
    /*
     * URL da API.
     *
     * No Docker, o serviço do backend deve ser acessível
     * pelo nome do serviço/container.
     */
    $apiBaseUrl = getenv('API_URL');

    if (!$apiBaseUrl) {
        $apiBaseUrl = 'http://app';
    }

    $apiBaseUrl = rtrim($apiBaseUrl, '/');

    $url = $apiBaseUrl . '/' . ltrim($rota, '/');

    $contexto = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
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
            "Não foi possível acessar a API em: {$url}"
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

    $dados = json_decode(
        $resposta,
        true
    );

    if (
        $statusCode < 200
        || $statusCode >= 300
    ) {
        $mensagem = is_array($dados)
            ? (
                $dados['erro']
                ?? $dados['message']
                ?? 'Erro retornado pela API.'
            )
            : 'Erro retornado pela API.';

        throw new RuntimeException(
            "API retornou HTTP {$statusCode}: {$mensagem}"
        );
    }

    if (!is_array($dados)) {
        throw new RuntimeException(
            'A API retornou uma resposta inválida. Resposta recebida: '
                . substr($resposta, 0, 500)
        );
    }

    return $dados;
}


/**
 * Busca produtos.
 *
 * Pode receber:
 * - null
 * - ID de categoria
 * - array de filtros
 */
function buscarProdutosApi($filtros = null): array
{
    $rota = '/produtos';

    if (is_int($filtros)) {

        if ($filtros > 0) {
            $rota .= '?categorias=' . $filtros;
        }

        return requisitarApi($rota);
    }

    /*
     * Sem filtros
     */
    if (!is_array($filtros) || empty($filtros)) {
        return requisitarApi($rota);
    }

    $parametros = [];

    /*
     * CATEGORIAS
     */
    if (!empty($filtros['categorias'])) {

        $categorias = is_array($filtros['categorias'])
            ? $filtros['categorias']
            : explode(',', (string) $filtros['categorias']);

        $categorias = array_filter(array_map('intval', $categorias));

        if (!empty($categorias)) {
            $parametros['categorias'] = implode(',', $categorias);
        }
    }

    /*
     * PREÇO MÍNIMO
     */
    if (
        isset($filtros['precoMin'])
        && $filtros['precoMin'] !== ''
        && $filtros['precoMin'] !== null
    ) {
        $parametros['precoMin'] =
            $filtros['precoMin'];
    }

    /*
     * PREÇO MÁXIMO
     */
    if (
        isset($filtros['precoMax'])
        && $filtros['precoMax'] !== ''
        && $filtros['precoMax'] !== null
    ) {
        $parametros['precoMax'] =
            $filtros['precoMax'];
    }

    /*
     * COR
     */
    if (
        isset($filtros['cor'])
        && $filtros['cor'] !== ''
        && $filtros['cor'] !== null
    ) {
        $parametros['cor'] =
            $filtros['cor'];
    }

    /*
     * TAMANHOS
     */
    if (!empty($filtros['tamanhos'])) {

        $tamanhos = array_filter(
            array_map(
                'strval',
                (array) $filtros['tamanhos']
            )
        );

        if (!empty($tamanhos)) {
            $parametros['tamanhos'] =
                implode(',', $tamanhos);
        }
    }

    /*
     * PRODUTO AUTORAL
     */
    if (
        isset($filtros['autoral'])
        && $filtros['autoral'] !== ''
        && $filtros['autoral'] !== null
    ) {
        $parametros['autoral'] =
            $filtros['autoral'];
    }

    /*
     * MONTA A QUERY STRING
     */
    if (!empty($parametros)) {

        $rota .= '?'
            . http_build_query($parametros);
    }

    error_log($rota);
    return requisitarApi($rota);
}


/**
 * Busca todas as categorias.
 */
function buscarCategoriasApi(): array
{
    return requisitarApi('/categorias');
}


/**
 * Busca as opções disponíveis para os filtros.
 */
function buscarFiltrosApi(): array
{
    return requisitarApi('/produtos/filtros');
}
