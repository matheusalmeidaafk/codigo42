<?php

namespace App\Controller;

use App\Config\OnlineDB;
use PDO;
use Throwable;
use InvalidArgumentException;

/** CRUD administrativo: nomes de tabelas/colunas sempre definidos no servidor. */
final class AdminResourceController
{
    private PDO $db;

    private const RESOURCES = [
        'produtos' => [
            'table' => 'produto', 'id' => 'id_produto',
            'fields' => ['nome', 'descricao', 'imagem_url', 'preco', 'is_autoral', 'ativo'],
            'required' => ['nome', 'descricao', 'imagem_url', 'preco'],
        ],
        'categorias' => [
            'table' => 'categoria', 'id' => 'id_categoria',
            'fields' => ['nome', 'id_categoria_pai'], 'required' => ['nome'],
        ],
        'banners' => [
            'table' => 'banner', 'id' => 'id_banner',
            'fields' => ['banner_url', 'link_url', 'ativo'], 'required' => ['banner_url'],
        ],
        'usuarios' => [
            'table' => 'usuario', 'id' => 'id_usuario',
            'fields' => ['nome_completo', 'email', 'telefone', 'cpf', 'imagem_url', 'ativo'],
            'required' => ['nome_completo', 'email', 'telefone', 'cpf'],
        ],
    ];

    public function __construct()
    {
        $this->db = (new OnlineDB())->conectar();
    }

    public function executar(string $recurso, string $metodo, ?int $id = null): void
    {
        try {
            if (!isset(self::RESOURCES[$recurso])) throw new InvalidArgumentException('Recurso inválido.');
            $config = self::RESOURCES[$recurso];
            if ($metodo === 'GET') {
                $colunas = $recurso === 'usuarios'
                    ? 'id_usuario, nome_completo, email, telefone, cpf, imagem_url, ativo'
                    : ($recurso === 'produtos'
                        ? 'produto.*, (SELECT GROUP_CONCAT(pc.id_categoria) FROM produto_categoria pc WHERE pc.id_produto = produto.id_produto) AS categoria_ids, (SELECT d.porcentagem_desconto FROM desconto d WHERE d.id_produto = produto.id_produto AND d.ativo = 1 ORDER BY d.id_desconto DESC LIMIT 1) AS desconto_percentual'
                        : '*');
                $sql = "SELECT {$colunas} FROM {$config['table']}";
                if ($id !== null) $sql .= " WHERE {$config['id']} = ?";
                else $sql .= " ORDER BY {$config['id']} DESC LIMIT 500";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($id !== null ? [$id] : []);
                $result = $id !== null ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($id !== null && !$result) { $this->responder(404, ['erro' => 'Registro não encontrado.']); return; }
                if ($recurso === 'produtos') {
                    $registros = $id === null ? $result : [$result];
                    if ($registros) {
                        $ids = array_map(static fn($r) => (int) $r['id_produto'], $registros);
                        $marks = implode(',', array_fill(0, count($ids), '?'));
                        $variacoes = $this->db->prepare("SELECT id_variacao, id_produto, tamanho, cor, preco, estoque, ativo FROM produto_variacao WHERE id_produto IN ($marks) ORDER BY id_variacao");
                        $variacoes->execute($ids);
                        $porProduto = [];
                        foreach ($variacoes->fetchAll(PDO::FETCH_ASSOC) as $v) $porProduto[(int) $v['id_produto']][] = $v;
                        foreach ($registros as &$registro) $registro['variacoes'] = $porProduto[(int) $registro['id_produto']] ?? [];
                        unset($registro);
                    }
                    $result = $id === null ? $registros : $registros[0];
                }
                $this->responder(200, $result);
                return;
            }

            if ($metodo === 'DELETE') {
                if ($id === null) throw new InvalidArgumentException('ID obrigatório.');
                if ($recurso === 'categorias') {
                    $used = $this->db->prepare('SELECT (SELECT COUNT(*) FROM categoria WHERE id_categoria_pai = ?) + (SELECT COUNT(*) FROM produto_categoria WHERE id_categoria = ?)');
                    $used->execute([$id, $id]);
                    if ((int) $used->fetchColumn() > 0) throw new InvalidArgumentException('Remova os vínculos com produtos e categorias filhas antes de excluir esta categoria.');
                }
                if (in_array('ativo', $config['fields'], true)) {
                    $sql = "UPDATE {$config['table']} SET ativo = 0 WHERE {$config['id']} = ?";
                } else {
                    $sql = "DELETE FROM {$config['table']} WHERE {$config['id']} = ?";
                }
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $this->responder($stmt->rowCount() ? 200 : 404, $stmt->rowCount()
                    ? ['mensagem' => 'Registro removido/desativado.'] : ['erro' => 'Registro não encontrado.']);
                return;
            }

            if (!in_array($metodo, ['POST', 'PUT'], true) || ($metodo === 'PUT' && $id === null)) {
                $this->responder(405, ['erro' => 'Método não permitido.']); return;
            }
            $dados = json_decode(file_get_contents('php://input'), true);
            if (!is_array($dados)) throw new InvalidArgumentException('JSON inválido.');
            $variacoes = null;
            $desconto = null;
            if ($recurso === 'produtos') {
                if (array_key_exists('variacoes', $dados)) {
                    if (!is_array($dados['variacoes']) || count($dados['variacoes']) > 100) throw new InvalidArgumentException('Lista de variações inválida.');
                    $variacoes = [];
                    $combinacoes = [];
                    foreach ($dados['variacoes'] as $v) {
                        if (!is_array($v)) throw new InvalidArgumentException('Variação inválida.');
                        $tamanho = trim((string) ($v['tamanho'] ?? ''));
                        $cor = trim((string) ($v['cor'] ?? ''));
                        $estoque = $v['estoque'] ?? 0;
                        if (strlen($tamanho) > 20 || strlen($cor) > 50 || filter_var($estoque, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
                            throw new InvalidArgumentException('Tamanho, cor ou estoque inválido.');
                        }
                        $comb = strtolower($tamanho . '\0' . $cor);
                        if (isset($combinacoes[$comb])) throw new InvalidArgumentException('Há tamanhos e cores repetidos nas variações.');
                        $combinacoes[$comb] = true;
                        $variacoes[] = [(int) ($v['id_variacao'] ?? 0), $tamanho ?: null, $cor ?: null, (int) $estoque];
                    }
                }
                if (array_key_exists('desconto_percentual', $dados)) {
                    $valor = $dados['desconto_percentual'];
                    if ($valor !== '' && $valor !== null && (!is_numeric($valor) || (float) $valor < 0 || (float) $valor > 100)) {
                        throw new InvalidArgumentException('Desconto deve estar entre 0 e 100%.');
                    }
                    $desconto = $valor === '' || $valor === null ? 0.0 : (float) $valor;
                }
            }
            $categoriaIds = null;
            if ($recurso === 'produtos' && array_key_exists('categoria_ids', $dados)) {
                if (!is_array($dados['categoria_ids'])) throw new InvalidArgumentException('Categorias inválidas.');
                $categoriaIds = array_values(array_unique(array_map('intval', $dados['categoria_ids'])));
                if (count($categoriaIds) !== count(array_filter($categoriaIds, static fn($item) => $item > 0))) {
                    throw new InvalidArgumentException('Selecione categorias válidas.');
                }
                if ($categoriaIds !== []) {
                    $marks = implode(',', array_fill(0, count($categoriaIds), '?'));
                    $check = $this->db->prepare("SELECT COUNT(*) FROM categoria WHERE id_categoria IN ($marks)");
                    $check->execute($categoriaIds);
                    if ((int) $check->fetchColumn() !== count($categoriaIds)) {
                        throw new InvalidArgumentException('Uma ou mais categorias não existem.');
                    }
                }
            }
            if ($recurso === 'produtos' && (($metodo === 'POST' && $categoriaIds === null) || $categoriaIds === [])) {
                throw new InvalidArgumentException('Selecione pelo menos uma categoria para o produto.');
            }
            $campos = array_intersect_key($dados, array_flip($config['fields']));
            if ($metodo === 'POST') {
                if ($recurso === 'usuarios') {
                    if (empty($dados['senha']) || strlen((string) $dados['senha']) < 8) {
                        throw new InvalidArgumentException('Senha deve ter no mínimo 8 caracteres.');
                    }
                    $campos['senha'] = password_hash((string) $dados['senha'], PASSWORD_DEFAULT);
                }
                foreach ($config['required'] as $required) {
                    if (!isset($campos[$required]) || trim((string) $campos[$required]) === '') {
                        throw new InvalidArgumentException("Campo obrigatório: {$required}.");
                    }
                }
            }
            foreach ($config['required'] as $required) {
                if (array_key_exists($required, $campos) && trim((string) $campos[$required]) === '') {
                    throw new InvalidArgumentException("Campo obrigatório: {$required}.");
                }
            }
            if (array_key_exists('email', $campos) && !filter_var($campos['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('E-mail inválido.');
            }
            if (array_key_exists('preco', $campos) && (!is_numeric($campos['preco']) || (float) $campos['preco'] < 0)) {
                throw new InvalidArgumentException('Preço inválido.');
            }
            foreach (['ativo', 'is_autoral'] as $boolean) {
                if (array_key_exists($boolean, $campos)) $campos[$boolean] = filter_var($campos[$boolean], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }
            if (array_key_exists('id_categoria_pai', $campos)) {
                $pai = $campos['id_categoria_pai'];
                if ($pai === '' || $pai === null) $campos['id_categoria_pai'] = null;
                elseif (!filter_var($pai, FILTER_VALIDATE_INT) || (int) $pai <= 0 || ($id && (int) $pai === $id)) {
                    throw new InvalidArgumentException('Categoria pai inválida.');
                } else {
                    $parentQuery = $this->db->prepare('SELECT id_categoria_pai FROM categoria WHERE id_categoria = ?');
                    $parentQuery->execute([(int) $pai]);
                    $parent = $parentQuery->fetch(PDO::FETCH_ASSOC);
                    if ($parent === false || $parent['id_categoria_pai'] !== null) {
                        throw new InvalidArgumentException('Selecione uma categoria principal existente.');
                    }
                    if ($id !== null) {
                        $children = $this->db->prepare('SELECT COUNT(*) FROM categoria WHERE id_categoria_pai = ?');
                        $children->execute([$id]);
                        if ((int) $children->fetchColumn() > 0) throw new InvalidArgumentException('Esta categoria possui filhas e deve permanecer como categoria pai.');
                    }
                    $campos['id_categoria_pai'] = (int) $pai;
                }
            }
            if ($campos === []) throw new InvalidArgumentException('Nenhum campo válido recebido.');
            $colunas = array_keys($campos);
            $this->db->beginTransaction();
            if ($metodo === 'POST') {
                $names = implode(', ', $colunas);
                $placeholders = implode(', ', array_fill(0, count($colunas), '?'));
                $stmt = $this->db->prepare("INSERT INTO {$config['table']} ({$names}) VALUES ({$placeholders})");
                $stmt->execute(array_values($campos));
                $savedId = (int) $this->db->lastInsertId();
            } else {
                $assignments = implode(', ', array_map(static fn($col) => "{$col} = ?", $colunas));
                $stmt = $this->db->prepare("UPDATE {$config['table']} SET {$assignments} WHERE {$config['id']} = ?");
                $stmt->execute([...array_values($campos), $id]);
                $savedId = $id;
            }
            if ($recurso === 'produtos' && $categoriaIds !== null) {
                $delete = $this->db->prepare('DELETE FROM produto_categoria WHERE id_produto = ?');
                $delete->execute([$savedId]);
                $insert = $this->db->prepare('INSERT INTO produto_categoria (id_produto, id_categoria) VALUES (?, ?)');
                foreach ($categoriaIds as $categoriaId) $insert->execute([$savedId, $categoriaId]);
            }
            if ($recurso === 'produtos' && $variacoes !== null) {
                // Mantém os IDs já usados no carrinho; variações retiradas ficam inativas.
                $existing = $this->db->prepare('SELECT id_variacao FROM produto_variacao WHERE id_produto = ?');
                $existing->execute([$savedId]);
                $existingIds = array_map('intval', $existing->fetchAll(PDO::FETCH_COLUMN));
                $seenIds = [];
                $update = $this->db->prepare('UPDATE produto_variacao SET tamanho = ?, cor = ?, estoque = ?, ativo = 1 WHERE id_variacao = ? AND id_produto = ?');
                $insert = $this->db->prepare('INSERT INTO produto_variacao (id_produto, tamanho, cor, estoque, ativo) VALUES (?, ?, ?, ?, 1)');
                foreach ($variacoes as [$variationId, $tamanho, $cor, $estoque]) {
                    if ($variationId > 0) {
                        if (!in_array($variationId, $existingIds, true) || in_array($variationId, $seenIds, true)) throw new InvalidArgumentException('Variação não pertence ao produto.');
                        $update->execute([$tamanho, $cor, $estoque, $variationId, $savedId]);
                        $seenIds[] = $variationId;
                    } else $insert->execute([$savedId, $tamanho, $cor, $estoque]);
                }
                $disable = $this->db->prepare('UPDATE produto_variacao SET ativo = 0 WHERE id_variacao = ? AND id_produto = ?');
                foreach (array_diff($existingIds, $seenIds) as $oldId) $disable->execute([$oldId, $savedId]);
            }
            if ($recurso === 'produtos' && $desconto !== null) {
                $this->db->prepare('UPDATE desconto SET ativo = 0 WHERE id_produto = ? AND ativo = 1')->execute([$savedId]);
                if ($desconto > 0) {
                    $insert = $this->db->prepare('INSERT INTO desconto (id_produto, porcentagem_desconto, ativo) VALUES (?, ?, 1)');
                    $insert->execute([$savedId, $desconto]);
                }
            }
            $this->db->commit();
            $this->responder($metodo === 'POST' ? 201 : 200, $metodo === 'POST'
                ? ['id' => $savedId] : ['mensagem' => 'Registro atualizado.']);
        } catch (InvalidArgumentException $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            $this->responder(400, ['erro' => $e->getMessage()]);
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            error_log('[ADMIN CRUD] ' . $e->getMessage());
            $this->responder(500, ['erro' => 'Não foi possível realizar a operação.']);
        }
    }

    private function responder(int $status, array $dados): void
    {
        http_response_code($status);
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    }
}
