<?php

require_once __DIR__ . '/../services/produtoApi.php';

/* Lê um parâmetro GET no formato "a,b,c" e devolve um array */
if (!function_exists('lerListaGet')) {
    function lerListaGet(string $chave): array
    {
        $valor = $_GET[$chave] ?? '';

        if (!is_string($valor) || $valor === '') {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode(',', $valor)),
            fn($v) => $v !== ''
        ));
    }
}

/* ---------- Dados dos filtros ---------- */

$filtros         = buscarFiltrosApi();
$categoriasTodas = buscarCategoriasApi();

$tipos      = $filtros['tipos']      ?? [];   // nomes das categorias pai
$categorias = $filtros['categorias'] ?? [];   // nomes das categorias filhas
$cores      = $filtros['cores']      ?? [];
$tamanhos   = $filtros['tamanhos']   ?? [];

$precoMinDisponivel = (float) ($filtros['precoMin'] ?? 0);
$precoMaxDisponivel = (float) ($filtros['precoMax'] ?? 0);

/* ---------- Mapas de categorias ---------- */

$categoriasPorNome = [];   // "Camisetas" => 1
$filhasPorPai      = [];   // 1 => [7, 4]

foreach ($categoriasTodas as $categoria) {
    $id   = (int) ($categoria['id_categoria'] ?? 0);
    $nome = trim((string) ($categoria['nome'] ?? ''));
    $pai  = $categoria['id_categoria_pai'] ?? null;

    if ($id <= 0 || $nome === '') {
        continue;
    }

    $categoriasPorNome[$nome] = $id;

    if ($pai !== null && $pai !== '') {
        $filhasPorPai[(int) $pai][] = $id;
    }
}

/* Converte lista de nomes em opções [valor => rótulo], onde valor = ID */
$nomesParaOpcoes = function (array $nomes) use ($categoriasPorNome): array {
    $opcoes = [];
    foreach ($nomes as $nome) {
        if (isset($categoriasPorNome[$nome])) {
            $opcoes[$categoriasPorNome[$nome]] = $nome;
        }
    }
    return $opcoes;
};

$opcoesTipos      = $nomesParaOpcoes($tipos);
$opcoesCategorias = $nomesParaOpcoes($categorias);

/* ---------- Selecionados (vindos da URL) ---------- */

$idsSelecionados      = array_map('intval', lerListaGet('categorias'));
$coresSelecionadas    = lerListaGet('cor');
$tamanhosSelecionados = lerListaGet('tamanho');

$precoMinSelecionado = (float) ($_GET['precoMin'] ?? $precoMinDisponivel);
$precoMaxSelecionado = (float) ($_GET['precoMax'] ?? $precoMaxDisponivel);

/* ---------- IDs enviados à API ---------- */
/*
 * Se o ID é de uma categoria pai, enviamos as filhas
 * (o produto é ligado à categoria filha).
 * Se é filha, enviamos o próprio ID.
 */

$categoriasApi = [];
foreach ($idsSelecionados as $id) {
    $categoriasApi[] = $id; // o próprio ID (pai ou filha)

    foreach ($filhasPorPai[$id] ?? [] as $idFilha) {
        $categoriasApi[] = $idFilha; // se for pai, inclui as filhas
    }
}

$categoriasApi = array_values(array_unique($categoriasApi));

/* ---------- Parâmetros para GET /produtos ---------- */

$filtrosProdutos = [
    'categorias' => implode(',', $categoriasApi),
    'cor'        => $coresSelecionadas[0] ?? '',   // backend aceita uma cor
    'tamanhos'   => implode(',', $tamanhosSelecionados),
];

if ($precoMinSelecionado > $precoMinDisponivel) {
    $filtrosProdutos['precoMin'] = $precoMinSelecionado;
}
if ($precoMaxSelecionado < $precoMaxDisponivel) {
    $filtrosProdutos['precoMax'] = $precoMaxSelecionado;
}

$filtrosProdutos = array_filter(
    $filtrosProdutos,
    fn($v) => $v !== '' && $v !== null
);

$produtos = buscarProdutosApi($filtrosProdutos);

/* ---------- Renderização dos checkboxes ---------- */
/*
 * $opcoes: [valor => rótulo]
 * $grupo : nome do parâmetro na URL (categorias, cor, tamanho)
 * Os inputs NÃO têm "name": quem monta a URL é o JS.
 */

if (!function_exists('renderFiltroCheckboxes')) {
    function renderFiltroCheckboxes(
        string $titulo,
        array $opcoes,
        string $grupo,
        string $id,
        array $selecionados = []
    ): void {
        $selecionados = array_map('strval', $selecionados);
?>
        <div class="bg-body-secondary rounded-1 p-2 mb-3">
            <button type="button"
                class="btn btn-sm w-100 d-flex justify-content-between align-items-center p-0 text-start border-0"
                data-bs-toggle="collapse"
                data-bs-target="#<?= htmlspecialchars($id) ?>"
                aria-expanded="true"
                aria-controls="<?= htmlspecialchars($id) ?>">
                <span class="fs-6"><?= htmlspecialchars($titulo) ?></span>
                <span class="fs-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-caret-up-fill filtro-seta" viewBox="0 0 16 16">
                        <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
                    </svg>
                </span>
            </button>

            <div id="<?= htmlspecialchars($id) ?>" class="collapse show mt-2">
                <?php foreach ($opcoes as $valor => $rotulo): ?>
                    <?php
                    $valor   = (string) $valor;
                    $inputId = $id . '-' . md5($valor);
                    $checked = in_array($valor, $selecionados, true);
                    ?>
                    <div class="form-check fs-6 mb-1">
                        <input class="form-check-input" type="checkbox"
                            data-grupo="<?= htmlspecialchars($grupo) ?>"
                            value="<?= htmlspecialchars($valor) ?>"
                            id="<?= htmlspecialchars($inputId) ?>"
                            <?= $checked ? 'checked' : '' ?>>
                        <label class="form-check-label" for="<?= htmlspecialchars($inputId) ?>">
                            <?= htmlspecialchars((string) $rotulo) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php
    }
}

/* Cores e tamanhos: valor = rótulo */
$opcoesCores    = array_combine($cores, $cores) ?: [];
$opcoesTamanhos = array_combine($tamanhos, $tamanhos) ?: [];
?>

<form class="filtros-produtos" id="formFiltros">
    <aside class="filtros-produtos" style="width: 220px;">

        <?php if (!empty($opcoesTipos)): ?>
            <?php renderFiltroCheckboxes('Produtos', $opcoesTipos, 'categorias', 'filtroProdutos', $idsSelecionados); ?>
        <?php endif; ?>

        <?php if (!empty($opcoesCores)): ?>
            <?php renderFiltroCheckboxes('Cores', $opcoesCores, 'cor', 'filtroCores', $coresSelecionadas); ?>
        <?php endif; ?>

        <?php if (!empty($opcoesTamanhos)): ?>
            <?php renderFiltroCheckboxes('Tamanhos', $opcoesTamanhos, 'tamanho', 'filtroTamanhos', $tamanhosSelecionados); ?>
        <?php endif; ?>
        <?php if ($precoMaxDisponivel > $precoMinDisponivel): ?>
            <div class="bg-body-secondary rounded-1 p-2 mb-3">

                <button type="button"
                    class="btn btn-sm w-100 d-flex justify-content-between align-items-center p-0 text-start border-0"
                    data-bs-toggle="collapse"
                    data-bs-target="#filtroPreco"
                    aria-expanded="true"
                    aria-controls="filtroPreco">
                    <span class="fs-6">Preço</span>
                    <span class="fs-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-caret-up-fill filtro-seta" viewBox="0 0 16 16">
                            <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
                        </svg>
                    </span>
                </button>

                <div id="filtroPreco" class="collapse show mt-2">

                    <div class="slider-preco px-1">
                        <div class="slider-preco-trilho"></div>
                        <div id="faixaPreco"></div>

                        <input type="range" id="precoMin"
                            min="<?= $precoMinDisponivel ?>" max="<?= $precoMaxDisponivel ?>"
                            value="<?= $precoMinSelecionado ?>" step="1">
                        <input type="range" id="precoMax"
                            min="<?= $precoMinDisponivel ?>" max="<?= $precoMaxDisponivel ?>"
                            value="<?= $precoMaxSelecionado ?>" step="1">
                    </div>

                    <div class="d-flex justify-content-between fs-6 mt-2 px-1">
                        <span>R$ <span id="precoMinTexto"><?= number_format($precoMinSelecionado, 2, ',', '.') ?></span></span>
                        <span>R$ <span id="precoMaxTexto"><?= number_format($precoMaxSelecionado, 2, ',', '.') ?></span></span>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($opcoesCategorias)): ?>
            <?php renderFiltroCheckboxes('Categorias', $opcoesCategorias, 'categorias', 'filtroCategorias', $idsSelecionados); ?>
        <?php endif; ?>

        <button type="submit" class="btn btn-success w-100 mt-2">Filtrar</button>

        <a href="<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'], '?')) ?>"
            class="btn btn-outline-secondary w-100 mt-2">Limpar filtros</a>
    </aside>
</form>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const minInput = document.getElementById("precoMin");
        const maxInput = document.getElementById("precoMax");
        const faixa = document.getElementById("faixaPreco");
        const minTexto = document.getElementById("precoMinTexto");
        const maxTexto = document.getElementById("precoMaxTexto");

        if (!minInput || !maxInput) return;

        const formatar = (v) =>
            Number(v).toLocaleString("pt-BR", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

        const atualizar = (origem) => {
            let min = parseFloat(minInput.value);
            let max = parseFloat(maxInput.value);

            // impede as alças de se cruzarem
            if (min > max) {
                if (origem === minInput) {
                    min = max;
                    minInput.value = min;
                } else {
                    max = min;
                    maxInput.value = max;
                }
            }

            const limiteMin = parseFloat(minInput.min);
            const limiteMax = parseFloat(minInput.max);
            const total = limiteMax - limiteMin;

            const esquerda = ((min - limiteMin) / total) * 100;
            const direita = ((max - limiteMin) / total) * 100;

            faixa.style.left = esquerda + "%";
            faixa.style.width = (direita - esquerda) + "%";

            minTexto.textContent = formatar(min);
            maxTexto.textContent = formatar(max);
        };

        minInput.addEventListener("input", () => atualizar(minInput));
        maxInput.addEventListener("input", () => atualizar(maxInput));

        atualizar();
    });

    document.getElementById('formFiltros').addEventListener('submit', function(event) {
        event.preventDefault();

        const partes = [];

        /* Junta os valores marcados de um grupo em "a,b,c" (sem codificar a vírgula) */
        const adicionarLista = (grupo) => {
            const valores = Array.from(
                this.querySelectorAll('input[data-grupo="' + grupo + '"]:checked')
            ).map(input => encodeURIComponent(input.value));

            if (valores.length > 0) {
                partes.push(grupo + '=' + valores.join(','));
            }
        };

        // tipos + categorias dividem o mesmo parâmetro: categorias=1,2,8
        adicionarLista('categorias');
        adicionarLista('cor');
        adicionarLista('tamanho');

        // preço: só vai na URL se o usuário saiu dos limites
        const precoMin = this.querySelector('#precoMin');
        const precoMax = this.querySelector('#precoMax');

        if (precoMin && Number(precoMin.value) > Number(precoMin.min)) {
            partes.push('precoMin=' + encodeURIComponent(precoMin.value));
        }
        if (precoMax && Number(precoMax.value) < Number(precoMax.max)) {
            partes.push('precoMax=' + encodeURIComponent(precoMax.value));
        }

        window.location.href =
            window.location.pathname + (partes.length ? '?' + partes.join('&') : '');
    });
</script>