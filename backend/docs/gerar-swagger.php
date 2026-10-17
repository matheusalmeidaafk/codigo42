<?php

declare(strict_types=1);

$arquivoRotas = __DIR__ . '/../src/public/index.php';
$arquivoSwagger = __DIR__ . '/openapi.yaml';

$rotasIgnoradas = [
    '/docs',
    '/docs/openapi.yaml'
];

echo PHP_EOL;
echo "==================================================" . PHP_EOL;
echo "       Código 42 - Sincronizador Swagger" . PHP_EOL;
echo "==================================================" . PHP_EOL;
echo PHP_EOL;

if (!file_exists($arquivoRotas)) {
    erro("index.php não encontrado: {$arquivoRotas}");
}

if (!file_exists($arquivoSwagger)) {
    erro("openapi.yaml não encontrado: {$arquivoSwagger}");
}

echo "[OK] index.php encontrado." . PHP_EOL;
echo "[OK] openapi.yaml encontrado." . PHP_EOL;

$conteudoRotas = file_get_contents($arquivoRotas);
$conteudoSwagger = file_get_contents($arquivoSwagger);

if ($conteudoRotas === false) {
    erro("Não foi possível ler o index.php.");
}

if ($conteudoSwagger === false) {
    erro("Não foi possível ler o openapi.yaml.");
}

echo PHP_EOL;
echo "[SWAGGER] Analisando index.php..." . PHP_EOL;
echo PHP_EOL;

$rotasApi = extrairRotasApi(
    $conteudoRotas,
    $rotasIgnoradas
);

foreach ($rotasApi as $rota) {
    echo "[ROTA] "
        . str_pad($rota['metodo'], 7)
        . " "
        . $rota['rota']
        . PHP_EOL;
}

echo PHP_EOL;

echo "[OK] "
    . count($rotasApi)
    . " rota(s) encontrada(s) na API."
    . PHP_EOL;

echo PHP_EOL;
echo "--------------------------------------------------" . PHP_EOL;
echo " Verificando rotas removidas" . PHP_EOL;
echo "--------------------------------------------------" . PHP_EOL;
echo PHP_EOL;

$rotasAutomaticas = extrairRotasAutomaticas(
    $conteudoSwagger
);

$rotasRemovidas = [];

foreach ($rotasAutomaticas as $rotaAutomatica) {
    if (!rotaExisteNaApi($rotaAutomatica, $rotasApi)) {
        echo "[REMOVIDA] "
            . str_pad($rotaAutomatica['metodo'], 7)
            . " "
            . $rotaAutomatica['rota']
            . PHP_EOL;

        $conteudoSwagger = removerMetodoAutomatico(
            $conteudoSwagger,
            $rotaAutomatica
        );

        $rotasRemovidas[] = $rotaAutomatica;
    }
}

if (empty($rotasRemovidas)) {
    echo "[OK] Nenhuma rota automática precisa ser removida." . PHP_EOL;
}

$rotasSwagger = extrairRotasSwagger(
    $conteudoSwagger
);

echo PHP_EOL;
echo "--------------------------------------------------" . PHP_EOL;
echo " Verificando rotas novas" . PHP_EOL;
echo "--------------------------------------------------" . PHP_EOL;
echo PHP_EOL;

$rotasNovas = [];

foreach ($rotasApi as $rota) {
    if (rotaExisteNoSwagger($rota, $rotasSwagger)) {
        echo "[OK]   "
            . str_pad($rota['metodo'], 7)
            . " "
            . $rota['rota']
            . PHP_EOL;

        continue;
    }

    echo "[NOVA] "
        . str_pad($rota['metodo'], 7)
        . " "
        . $rota['rota']
        . PHP_EOL;

    $rotasNovas[] = $rota;
}

if (!empty($rotasNovas)) {
    echo PHP_EOL;
    echo "[SWAGGER] Adicionando rotas novas..." . PHP_EOL;
    echo PHP_EOL;

    $rotasAgrupadas = [];

    foreach ($rotasNovas as $rota) {
        $path = $rota['rota'];

        if (!isset($rotasAgrupadas[$path])) {
            $rotasAgrupadas[$path] = [];
        }

        $rotasAgrupadas[$path][] = $rota;
    }

    foreach ($rotasAgrupadas as $path => $metodos) {
        if (pathExisteNoSwagger($path, $conteudoSwagger)) {
            foreach ($metodos as $rota) {
                $conteudoSwagger = adicionarMetodoEmPathExistente(
                    $conteudoSwagger,
                    $rota
                );

                echo "[ADICIONADA] "
                    . str_pad($rota['metodo'], 7)
                    . " "
                    . $rota['rota']
                    . PHP_EOL;
            }

            continue;
        }

        $conteudoSwagger = rtrim($conteudoSwagger);

        $conteudoSwagger .= PHP_EOL;
        $conteudoSwagger .= PHP_EOL;

        $conteudoSwagger .= gerarBlocoPath(
            $path,
            $metodos
        );

        foreach ($metodos as $rota) {
            echo "[ADICIONADA] "
                . str_pad($rota['metodo'], 7)
                . " "
                . $rota['rota']
                . PHP_EOL;
        }
    }
}

$houveAlteracao =
    !empty($rotasNovas)
    || !empty($rotasRemovidas);

if (!$houveAlteracao) {
    echo PHP_EOL;
    echo "==================================================" . PHP_EOL;
    echo "[OK] Swagger já está sincronizado." . PHP_EOL;
    echo "==================================================" . PHP_EOL;
    echo PHP_EOL;

    exit(0);
}

$resultado = file_put_contents(
    $arquivoSwagger,
    rtrim($conteudoSwagger) . PHP_EOL
);

if ($resultado === false) {
    erro("Não foi possível salvar openapi.yaml.");
}

echo PHP_EOL;
echo "==================================================" . PHP_EOL;
echo " Swagger sincronizado com sucesso" . PHP_EOL;
echo "==================================================" . PHP_EOL;
echo PHP_EOL;

echo "Rotas adicionadas: "
    . count($rotasNovas)
    . PHP_EOL;

echo "Rotas removidas: "
    . count($rotasRemovidas)
    . PHP_EOL;

echo PHP_EOL;

exit(0);

function erro(string $mensagem): never
{
    echo PHP_EOL;
    echo "[ERRO] {$mensagem}" . PHP_EOL;
    echo PHP_EOL;

    exit(1);
}

function extrairRotasApi(
    string $conteudo,
    array $rotasIgnoradas
): array {
    $rotas = [];

    $padraoEstatico =
        '/\$method\s*===\s*["\']'
        . '(GET|POST|PUT|PATCH|DELETE)'
        . '["\']'
        . '\s*&&\s*'
        . '\$uri\s*===\s*["\']'
        . '([^"\']+)'
        . '["\']'
        . '/i';

    preg_match_all(
        $padraoEstatico,
        $conteudo,
        $matches,
        PREG_SET_ORDER
    );

    foreach ($matches as $match) {
        adicionarRota(
            $rotas,
            strtoupper($match[1]),
            $match[2],
            $rotasIgnoradas
        );
    }

    $padraoDinamico =
        '/\$method\s*===\s*["\']'
        . '(GET|POST|PUT|PATCH|DELETE)'
        . '["\']'
        . '\s*&&\s*'
        . 'preg_match\s*\(\s*'
        . '["\']([^"\']+)["\']'
        . '\s*,\s*\$uri'
        . '/i';

    preg_match_all(
        $padraoDinamico,
        $conteudo,
        $matches,
        PREG_SET_ORDER
    );

    foreach ($matches as $match) {
        $metodo = strtoupper($match[1]);

        $rota = converterRegexParaRota(
            $match[2]
        );

        if ($rota === null) {
            continue;
        }

        adicionarRota(
            $rotas,
            $metodo,
            $rota,
            $rotasIgnoradas
        );
    }

    usort(
        $rotas,
        function (
            array $a,
            array $b
        ): int {
            $comparacao = strcmp(
                $a['rota'],
                $b['rota']
            );

            if ($comparacao !== 0) {
                return $comparacao;
            }

            return strcmp(
                $a['metodo'],
                $b['metodo']
            );
        }
    );

    return $rotas;
}

function adicionarRota(
    array &$rotas,
    string $metodo,
    string $rota,
    array $rotasIgnoradas
): void {
    if (in_array($rota, $rotasIgnoradas, true)) {
        return;
    }

    foreach ($rotas as $existente) {
        if (
            $existente['metodo'] === $metodo
            && $existente['rota'] === $rota
        ) {
            return;
        }
    }

    $rotas[] = [
        'metodo' => $metodo,
        'rota' => $rota
    ];
}

function converterRegexParaRota(
    string $regex
): ?string {
    $regex = trim($regex);

    if (
        str_starts_with($regex, '#')
        && str_ends_with($regex, '#')
    ) {
        $regex = substr(
            $regex,
            1,
            -1
        );
    }

    $regex = preg_replace(
        '/^\^/',
        '',
        $regex
    );

    $regex = preg_replace(
        '/\$$/',
        '',
        $regex
    );

    if (str_contains($regex, '(\d+)')) {
        $nome = descobrirNomeParametroNumerico(
            $regex
        );

        $regex = preg_replace(
            '/\(\\\\d\+\)/',
            '{' . $nome . '}',
            $regex,
            1
        );
    }

    if (str_contains($regex, '([^/]+)')) {
        $nome = descobrirNomeParametroTexto(
            $regex
        );

        $regex = str_replace(
            '([^/]+)',
            '{' . $nome . '}',
            $regex
        );
    }

    if (
        str_contains($regex, '(')
        || str_contains($regex, ')')
    ) {
        echo "[AVISO] Regex não reconhecida: "
            . $regex
            . PHP_EOL;

        return null;
    }

    return $regex;
}

function descobrirNomeParametroNumerico(
    string $regex
): string {
    return 'id';
}

function descobrirNomeParametroTexto(
    string $regex
): string {
    if (str_contains($regex, '/produtos/')) {
        return 'pesquisa';
    }

    return 'parametro';
}

function extrairRotasSwagger(
    string $conteudo
): array {
    $rotas = [];

    $linhas = preg_split(
        '/\R/',
        $conteudo
    );

    $dentroPaths = false;
    $rotaAtual = null;

    foreach ($linhas as $linha) {
        if (trim($linha) === 'paths:') {
            $dentroPaths = true;

            continue;
        }

        if (!$dentroPaths) {
            continue;
        }

        if (
            preg_match(
                '/^\s{2}(\/[^:]+):\s*$/',
                $linha,
                $match
            )
        ) {
            $rotaAtual = trim(
                $match[1]
            );

            continue;
        }

        if (
            $rotaAtual !== null
            && preg_match(
                '/^\s{4}'
                . '(get|post|put|patch|delete):'
                . '\s*$/i',
                $linha,
                $match
            )
        ) {
            $rotas[] = [
                'metodo' => strtoupper(
                    $match[1]
                ),
                'rota' => $rotaAtual
            ];
        }
    }

    return $rotas;
}

function extrairRotasAutomaticas(
    string $conteudo
): array {
    $rotas = [];

    $linhas = preg_split(
        '/\R/',
        $conteudo
    );

    $dentroPaths = false;
    $pathAtual = null;
    $metodoAtual = null;
    $metodoAutomatico = false;

    foreach ($linhas as $linha) {
        if (trim($linha) === 'paths:') {
            $dentroPaths = true;

            continue;
        }

        if (!$dentroPaths) {
            continue;
        }

        if (
            preg_match(
                '/^\s{2}(\/[^:]+):\s*$/',
                $linha,
                $match
            )
        ) {
            if (
                $pathAtual !== null
                && $metodoAtual !== null
                && $metodoAutomatico
            ) {
                adicionarRotaAutomatica(
                    $rotas,
                    $metodoAtual,
                    $pathAtual
                );
            }

            $pathAtual = trim(
                $match[1]
            );

            $metodoAtual = null;
            $metodoAutomatico = false;

            continue;
        }

        if (
            preg_match(
                '/^\s{4}'
                . '(get|post|put|patch|delete):'
                . '\s*$/i',
                $linha,
                $match
            )
        ) {
            if (
                $pathAtual !== null
                && $metodoAtual !== null
                && $metodoAutomatico
            ) {
                adicionarRotaAutomatica(
                    $rotas,
                    $metodoAtual,
                    $pathAtual
                );
            }

            $metodoAtual = strtoupper(
                $match[1]
            );

            $metodoAutomatico = false;

            continue;
        }

        if (
            $metodoAtual !== null
            && preg_match(
                '/^\s{6}x-auto-generated:\s*true\s*$/i',
                $linha
            )
        ) {
            $metodoAutomatico = true;
        }
    }

    if (
        $pathAtual !== null
        && $metodoAtual !== null
        && $metodoAutomatico
    ) {
        adicionarRotaAutomatica(
            $rotas,
            $metodoAtual,
            $pathAtual
        );
    }

    return $rotas;
}

function adicionarRotaAutomatica(
    array &$rotas,
    string $metodo,
    string $path
): void {
    foreach ($rotas as $rota) {
        if (
            $rota['metodo'] === $metodo
            && $rota['rota'] === $path
        ) {
            return;
        }
    }

    $rotas[] = [
        'metodo' => $metodo,
        'rota' => $path
    ];
}

function rotaExisteNaApi(
    array $rota,
    array $rotasApi
): bool {
    foreach ($rotasApi as $rotaApi) {
        if (
            $rotaApi['metodo'] === $rota['metodo']
            && $rotaApi['rota'] === $rota['rota']
        ) {
            return true;
        }
    }

    return false;
}

function rotaExisteNoSwagger(
    array $rota,
    array $rotasSwagger
): bool {
    foreach ($rotasSwagger as $swagger) {
        if (
            $swagger['metodo'] === $rota['metodo']
            && $swagger['rota'] === $rota['rota']
        ) {
            return true;
        }
    }

    return false;
}

function pathExisteNoSwagger(
    string $path,
    string $conteudo
): bool {
    return localizarBlocoPath(
        $conteudo,
        $path
    ) !== null;
}

function localizarBlocoPath(
    string $conteudo,
    string $path
): ?array {
    $pathEscapado = preg_quote(
        $path,
        '~'
    );

    $padrao =
        '~'
        . '(^ {2}'
        . $pathEscapado
        . ':\s*$)'
        . '(.*?)'
        . '(?=^ {2}/|\z)'
        . '~ms';

    if (
        !preg_match(
            $padrao,
            $conteudo,
            $match,
            PREG_OFFSET_CAPTURE
        )
    ) {
        return null;
    }

    return [
        'conteudo' => $match[0][0],
        'posicao' => $match[0][1]
    ];
}

function gerarBlocoPath(
    string $path,
    array $rotas
): string {
    $yaml = "  {$path}:" . PHP_EOL;

    foreach ($rotas as $rota) {
        $yaml .= gerarBlocoMetodo(
            $rota
        );
    }

    return $yaml;
}

function gerarBlocoMetodo(
    array $rota
): string {
    $metodo = strtolower(
        $rota['metodo']
    );

    $path = $rota['rota'];

    $yaml = '';

    $yaml .= "    {$metodo}:" . PHP_EOL;
    $yaml .= "      x-auto-generated: true" . PHP_EOL;
    $yaml .= "      tags:" . PHP_EOL;
    $yaml .= "        - Auto" . PHP_EOL;
    $yaml .= "      summary: Rota detectada automaticamente" . PHP_EOL;
    $yaml .= "      description: >-" . PHP_EOL;
    $yaml .= "        Esta rota foi detectada automaticamente a partir do index.php." . PHP_EOL;

    $parametros = extrairParametrosPath(
        $path
    );

    if (!empty($parametros)) {
        $yaml .= "      parameters:" . PHP_EOL;

        foreach ($parametros as $parametro) {
            $yaml .= "        - name: {$parametro}" . PHP_EOL;
            $yaml .= "          in: path" . PHP_EOL;
            $yaml .= "          required: true" . PHP_EOL;
            $yaml .= "          schema:" . PHP_EOL;

            if ($parametro === 'id') {
                $yaml .= "            type: integer" . PHP_EOL;
                $yaml .= "            format: int32" . PHP_EOL;
            } else {
                $yaml .= "            type: string" . PHP_EOL;
            }
        }
    }

    if (
        in_array(
            strtoupper($rota['metodo']),
            [
                'POST',
                'PUT',
                'PATCH'
            ],
            true
        )
    ) {
        $yaml .= "      requestBody:" . PHP_EOL;
        $yaml .= "        required: false" . PHP_EOL;
        $yaml .= "        content:" . PHP_EOL;
        $yaml .= "          application/json:" . PHP_EOL;
        $yaml .= "            schema:" . PHP_EOL;
        $yaml .= "              type: object" . PHP_EOL;
        $yaml .= "              additionalProperties: true" . PHP_EOL;
    }

    $yaml .= "      responses:" . PHP_EOL;

    $yaml .= "        '200':" . PHP_EOL;
    $yaml .= "          description: Requisição realizada com sucesso" . PHP_EOL;

    $yaml .= "        '400':" . PHP_EOL;
    $yaml .= "          description: Requisição inválida" . PHP_EOL;

    $yaml .= "        '404':" . PHP_EOL;
    $yaml .= "          description: Recurso não encontrado" . PHP_EOL;

    $yaml .= PHP_EOL;

    return $yaml;
}

function extrairParametrosPath(
    string $path
): array {
    preg_match_all(
        '/\{([^}]+)\}/',
        $path,
        $matches
    );

    return $matches[1] ?? [];
}

function adicionarMetodoEmPathExistente(
    string $conteudo,
    array $rota
): string {
    $bloco = localizarBlocoPath(
        $conteudo,
        $rota['rota']
    );

    if ($bloco === null) {
        echo "[AVISO] Path não localizado: "
            . $rota['rota']
            . PHP_EOL;

        return $conteudo;
    }

    $metodo = strtolower(
        $rota['metodo']
    );

    if (
        preg_match(
            '/^ {4}'
            . preg_quote($metodo, '/')
            . ':\s*$/m',
            $bloco['conteudo']
        )
    ) {
        return $conteudo;
    }

    $novoBloco =
        rtrim($bloco['conteudo'])
        . PHP_EOL
        . gerarBlocoMetodo($rota);

    return substr_replace(
        $conteudo,
        $novoBloco,
        $bloco['posicao'],
        strlen($bloco['conteudo'])
    );
}

function removerMetodoAutomatico(
    string $conteudo,
    array $rota
): string {
    $bloco = localizarBlocoPath(
        $conteudo,
        $rota['rota']
    );

    if ($bloco === null) {
        return $conteudo;
    }

    $conteudoPath = $bloco['conteudo'];

    $metodo = strtolower(
        $rota['metodo']
    );

    $metodoEscapado = preg_quote(
        $metodo,
        '~'
    );

    $padraoMetodo =
        '~'
        . '^ {4}'
        . $metodoEscapado
        . ':\s*$'
        . '.*?'
        . '(?=^ {4}(?:get|post|put|patch|delete):\s*$|\z)'
        . '~msi';

    if (
        !preg_match(
            $padraoMetodo,
            $conteudoPath,
            $matchMetodo,
            PREG_OFFSET_CAPTURE
        )
    ) {
        return $conteudo;
    }

    $conteudoMetodo = $matchMetodo[0][0];

    if (
        !preg_match(
            '/^ {6}x-auto-generated:\s*true\s*$/mi',
            $conteudoMetodo
        )
    ) {
        echo "[PROTEGIDA] "
            . $rota['metodo']
            . " "
            . $rota['rota']
            . PHP_EOL;

        return $conteudo;
    }

    $novoConteudoPath = substr_replace(
        $conteudoPath,
        '',
        $matchMetodo[0][1],
        strlen($conteudoMetodo)
    );

    $possuiMetodos =
        preg_match(
            '/^ {4}(get|post|put|patch|delete):\s*$/mi',
            $novoConteudoPath
        ) === 1;

    if (!$possuiMetodos) {
        return substr_replace(
            $conteudo,
            '',
            $bloco['posicao'],
            strlen($bloco['conteudo'])
        );
    }

    return substr_replace(
        $conteudo,
        rtrim($novoConteudoPath) . PHP_EOL,
        $bloco['posicao'],
        strlen($bloco['conteudo'])
    );
}