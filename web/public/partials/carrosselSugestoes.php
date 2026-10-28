<?php

require_once __DIR__ . '/../../components/carrosselProdutos.php';

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo 'Método não permitido.';
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
if (!is_array($dados) || !isset($dados['produtos']) || !is_array($dados['produtos'])) {
    http_response_code(400);
    echo 'Lista de produtos inválida.';
    exit;
}

$produtos = [];
foreach (array_slice($dados['produtos'], 0, 10) as $produto) {
    if (!is_array($produto)) {
        continue;
    }

    $idProduto = (int) ($produto['id_produto'] ?? $produto['id'] ?? 0);
    $nome = trim((string) ($produto['nome'] ?? ''));
    if ($idProduto <= 0 || $nome === '') {
        continue;
    }

    $preco = $produto['preco'] ?? 0;
    $precoFinal = $produto['preco_final'] ?? $preco;
    if (!is_numeric($preco) || !is_numeric($precoFinal)) {
        continue;
    }

    $imagem = (string) (
        $produto['variacao_imagem_url']
        ?? $produto['imagem_url']
        ?? 'https://placehold.co/600x600?text=Sem+Imagem'
    );
    if (!preg_match('#^https?://#i', $imagem)) {
        $imagem = 'https://placehold.co/600x600?text=Sem+Imagem';
    }

    $tamanhos = $produto['tamanhos'] ?? [];
    if (!is_array($tamanhos)) {
        $tamanhos = [];
    }

    $desconto = $produto['porcentagem_desconto'] ?? null;
    $produtos[] = [
        'id_produto' => $idProduto,
        'nome' => $nome,
        'preco' => (float) $preco,
        'preco_final' => (float) $precoFinal,
        'estrelas' => max(0, min(5, (float) ($produto['estrelas'] ?? 0))),
        'porcentagem_desconto' => is_numeric($desconto) ? (float) $desconto : null,
        'variacao_imagem_url' => $imagem,
        'tamanhos' => array_values(array_filter($tamanhos, 'is_string')),
    ];
}

if ($produtos === []) {
    http_response_code(422);
    echo 'Nenhum produto válido para exibir.';
    exit;
}

// Reutiliza o carrossel e os cards existentes, sem duplicar o HTML no JS.
renderCarrosselProdutos($produtos, 'carrosselSugestoes');