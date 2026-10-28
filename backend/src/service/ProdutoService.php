<?php

namespace App\Service;

use App\Config\OnlineDB;
use App\Model\Produto;
use Exception;
use PDO;
use Throwable;

class ProdutoService
{
    private PDO $db;

    public function __construct()
    {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    public function criar(
        string $imagemUrl,
        string $nome,
        string $descricao,
        float $preco,
        bool $ativo,
        bool $isAutoral,
        array $categoriaIds = []
    ): Produto {
        if ($imagemUrl === '') {
            throw new Exception('Imagem do produto é obrigatória.');
        }

        if ($nome === '') {
            throw new Exception('Nome do produto é obrigatório.');
        }

        if ($descricao === '') {
            throw new Exception('Descrição do produto é obrigatória.');
        }

        if ($preco < 0) {
            throw new Exception('Preço do produto não pode ser negativo.');
        }

        $categoriaIds = array_values(array_unique(array_map(
            'intval',
            $categoriaIds
        )));

        foreach ($categoriaIds as $categoriaId) {
            if ($categoriaId <= 0 || !$this->categoriaExiste($categoriaId)) {
                throw new Exception(
                    "Categoria {$categoriaId} não encontrada."
                );
            }
        }

        $this->db->beginTransaction();

        try {
            $sql = <<<'SQL'
                INSERT INTO produto (
                    imagem_url,
                    nome,
                    descricao,
                    preco,
                    ativo
                ) VALUES (?, ?, ?, ?, ?)
            SQL;

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $imagemUrl,
                $nome,
                $descricao,
                $preco,
                $ativo,
            ]);

            $id = (int) $this->db->lastInsertId();

            if ($categoriaIds !== []) {
                $sqlCategoria = <<<'SQL'
                    INSERT INTO produto_categoria (
                        id_produto,
                        id_categoria
                    ) VALUES (?, ?)
                SQL;

                $stmtCategoria = $this->db->prepare($sqlCategoria);

                foreach ($categoriaIds as $categoriaId) {
                    $stmtCategoria->execute([
                        $id,
                        $categoriaId,
                    ]);
                }
            }

            $this->db->commit();

            return new Produto(
                $nome,
                $descricao,
                $preco,
                $ativo,
                $isAutoral,
                $id,
                $imagemUrl,
            );
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function listar(
        ?string $categoria = null,
        ?int $categoriaId = null
    ): array {

        $params = [];

        $sql = <<<'SQL'
        SELECT
            p.id_produto,
            p.imagem_url,
            p.nome,
            p.descricao,
            p.preco,
            p.is_autoral,
            p.ativo,

            COALESCE(
                ROUND(AVG(a.estrelas), 1),
                0
            ) AS estrelas,

            d.porcentagem_desconto,

            CASE
                WHEN d.porcentagem_desconto IS NOT NULL
                THEN ROUND(
                    p.preco - (
                        p.preco * d.porcentagem_desconto / 100
                    ),
                    2
                )
                ELSE p.preco
            END AS preco_final

        FROM produto p

        LEFT JOIN avaliacao_produto a
            ON a.id_produto = p.id_produto

        LEFT JOIN desconto d
            ON d.id_produto = p.id_produto
            AND d.ativo = TRUE

        WHERE p.ativo = 1
    SQL;

        if ($categoriaId !== null) {

            $sql .= <<<'SQL'

            AND EXISTS (
                SELECT 1
                FROM produto_categoria pc_filtro
                WHERE pc_filtro.id_produto = p.id_produto
                  AND pc_filtro.id_categoria = ?
            )
        SQL;

            $params[] = $categoriaId;
        } elseif ($categoria !== null) {

            $sql .= <<<'SQL'

            AND EXISTS (
                SELECT 1
                FROM produto_categoria pc_filtro
                INNER JOIN categoria c_filtro
                    ON c_filtro.id_categoria = pc_filtro.id_categoria
                WHERE pc_filtro.id_produto = p.id_produto
                  AND LOWER(c_filtro.nome) = LOWER(?)
            )
        SQL;

            $params[] = $categoria;
        }

        $sql .= <<<'SQL'

        GROUP BY
            p.id_produto,
            p.imagem_url,
        p.nome,
            p.descricao,
            p.preco,
            p.is_autoral,
            p.ativo,
            d.porcentagem_desconto

        ORDER BY p.id_produto DESC
    SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($produtos === []) {
            return [];
        }

        $categoriasPorProduto = $this->buscarCategoriasDosProdutos(
            array_column($produtos, 'id_produto')
        );

        foreach ($produtos as &$produto) {

            $idProduto = (int) $produto['id_produto'];

            $produto['id_produto'] = $idProduto;
            $produto['preco'] = (float) $produto['preco'];
            $produto['preco_final'] = (float) $produto['preco_final'];
            $produto['estrelas'] = (float) $produto['estrelas'];
            $produto['is_autoral'] = (bool) $produto['is_autoral'];
            $produto['ativo'] = (bool) $produto['ativo'];

            $produto['porcentagem_desconto'] =
                $produto['porcentagem_desconto'] !== null
                ? (float) $produto['porcentagem_desconto']
                : null;

            $produto['categorias'] =
                $categoriasPorProduto[$idProduto] ?? [];
        }

        unset($produto);

        return $produtos;
    }

    public function filtrarCategoria(
        $precoMin,
        $precoMax,
        $isAutoral,
        $cor,
        array $tamanho = [],
        array $categorias = []
    ): array {
        $sql = "
        SELECT
            p.*,
            COALESCE(av.estrelas, 0) AS estrelas,
            COALESCE(av.total_avaliacoes, 0) AS total_avaliacoes,
            pv.id_variacao,
            pv.tamanho,
            pv.cor, 
            i.imagem_url AS variacao_imagem_url,
            d.porcentagem_desconto,

            CASE
                WHEN d.porcentagem_desconto IS NOT NULL THEN
                    ROUND(
                        p.preco - (
                            p.preco * d.porcentagem_desconto / 100
                        ),
                        2
                    )
                ELSE p.preco
            END AS preco_final
        FROM produto p
        LEFT JOIN (
            SELECT id_produto, ROUND(AVG(estrelas), 1) AS estrelas,
                   COUNT(*) AS total_avaliacoes
            FROM avaliacao_produto
            GROUP BY id_produto
        ) av ON av.id_produto = p.id_produto

        LEFT JOIN (
            SELECT
                id_variacao,
                id_produto,
                tamanho,
                cor
            FROM (
                SELECT
                    pv.id_variacao,
                    pv.id_produto,
                    pv.tamanho,
                    pv.cor,
                    ROW_NUMBER() OVER (
                        PARTITION BY pv.id_produto, pv.cor
                        ORDER BY pv.id_variacao
                    ) AS rn
                FROM produto_variacao pv
                WHERE pv.ativo = 1
            ) AS variacoes
            WHERE rn = 1
        ) pv
            ON pv.id_produto = p.id_produto

        LEFT JOIN imagem_variacao i
            ON i.id_variacao = pv.id_variacao
        
        LEFT JOIN desconto d
            ON d.id_produto = p.id_produto
            AND d.ativo = TRUE

        WHERE p.ativo = TRUE
    ";

        $params = [];

        if (!empty($categorias)) {
            $placeholders = implode(
                ',',
                array_fill(0, count($categorias), '?')
            );

            $sql .= "
        AND EXISTS (
            SELECT 1
            FROM produto_categoria pc
            WHERE pc.id_produto = p.id_produto
              AND pc.id_categoria IN (SELECT id_categoria FROM categoria WHERE id_categoria IN ($placeholders) OR id_categoria_pai IN ($placeholders))
        )
    ";

            $params = array_merge($params, $categorias, $categorias);
        }

        if (!empty($precoMin)) {
            $sql .= " AND p.preco >= ?";
            $params[] = (float) $precoMin;
        }

        if (!empty($precoMax)) {
            $sql .= " AND p.preco <= ?";
            $params[] = (float) $precoMax;
        }

        if ($isAutoral !== null) {
            $sql .= " AND p.is_autoral = ?";

            $isAutoral = filter_var(
                $isAutoral,
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );

            $params[] = (bool) $isAutoral;
        }

        if (!empty($tamanho)) {
            $placeholders = implode(',', array_fill(0, count($tamanho), '?'));

            $sql .= " AND EXISTS (SELECT 1 FROM produto_variacao tamanho_filtro WHERE tamanho_filtro.id_produto = p.id_produto AND tamanho_filtro.ativo = 1 AND tamanho_filtro.tamanho IN ($placeholders))";

            $params = array_merge($params, $tamanho);
        }

        if (!empty($cor)) {
            $sql .= " AND pv.cor = ?";
            $params[] = $cor;
        }

        $sql .= " ORDER BY p.id_produto DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $variationQuery = $this->db->prepare('SELECT id_variacao, tamanho, cor, estoque FROM produto_variacao WHERE id_produto = ? AND ativo = 1 ORDER BY id_variacao');
        foreach ($produtos as &$produto) {

            $produto['estrelas'] = (float) $produto['estrelas'];
            $produto['total_avaliacoes'] = (int) $produto['total_avaliacoes'];
            $sqlTamanhos = "
        SELECT DISTINCT tamanho
        FROM produto_variacao
        WHERE id_produto = ? AND ativo = 1
          AND tamanho IS NOT NULL
          AND tamanho <> ''
        ORDER BY tamanho
    ";

            $stmtTamanhos = $this->db->prepare($sqlTamanhos);
            $stmtTamanhos->execute([
                $produto['id_produto']
            ]);

            $variationQuery->execute([$produto['id_produto']]);
            $produto['variacoes'] = $variationQuery->fetchAll(PDO::FETCH_ASSOC);
            $produto['tamanhos'] = $stmtTamanhos->fetchAll(
                PDO::FETCH_COLUMN
            );
        }

        unset($produto);

        return $produtos;
    }




    private function categoriaExiste(int $categoriaId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM categoria WHERE id_categoria = ? LIMIT 1'
        );

        $stmt->execute([$categoriaId]);

        return (bool) $stmt->fetchColumn();
    }

    private function buscarCategoriasDosProdutos(array $produtoIds): array
    {
        $produtoIds = array_values(array_unique(array_map(
            'intval',
            $produtoIds
        )));

        if ($produtoIds === []) {
            return [];
        }

        $placeholders = implode(
            ', ',
            array_fill(0, count($produtoIds), '?')
        );

        $sql = "
            SELECT
                pc.id_produto,
                c.id_categoria,
                c.nome
            FROM produto_categoria pc
            INNER JOIN categoria c
                ON c.id_categoria = pc.id_categoria
            WHERE pc.id_produto IN ({$placeholders})
            ORDER BY c.nome
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($produtoIds);

        $categoriasPorProduto = [];

        foreach ($stmt->fetchAll() as $categoria) {
            $idProduto = (int) $categoria['id_produto'];

            $categoriasPorProduto[$idProduto][] = [
                'id_categoria' => (int) $categoria['id_categoria'],
                'nome' => $categoria['nome'],
            ];
        }

        return $categoriasPorProduto;
    }

    public function pesquisar(string $pesquisa): array
    {
        $sql = "SELECT * FROM produto WHERE nome LIKE ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(["%" . $pesquisa . "%"]);

        return $stmt->fetchAll();
    }

    public function listarFiltros(): array
    {
        $filtros = [];

        // PRODUTOS = categorias pai
        $sql = "
        SELECT
            c.nome
        FROM categoria c
        WHERE c.id_categoria_pai IS NULL
        ORDER BY c.nome
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $filtros['tipos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        // CATEGORIAS = categorias filhas
        $sql = "
        SELECT
            c.nome
        FROM categoria c
        WHERE c.id_categoria_pai IS NOT NULL
        ORDER BY c.nome
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $filtros['categorias'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        // CORES
        $sql = "
        SELECT DISTINCT pv.cor
        FROM produto_variacao pv
        INNER JOIN produto p
            ON p.id_produto = pv.id_produto
        WHERE p.ativo = 1
          AND pv.ativo = 1
          AND pv.cor IS NOT NULL
          AND pv.cor <> ''
        ORDER BY pv.cor
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $filtros['cores'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        // TAMANHOS
        $sql = "
        SELECT DISTINCT pv.tamanho
        FROM produto_variacao pv
        INNER JOIN produto p
            ON p.id_produto = pv.id_produto
        WHERE p.ativo = 1
          AND pv.ativo = 1
          AND pv.tamanho IS NOT NULL
          AND pv.tamanho <> ''
        ORDER BY pv.tamanho
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $filtros['tamanhos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);


        // PREÇO
        $sql = "
        SELECT
            MIN(preco_final) AS precoMin,
            MAX(preco_final) AS precoMax
        FROM (
            SELECT
                COALESCE(pv.preco, p.preco) AS preco_final
            FROM produto p
            LEFT JOIN produto_variacao pv
                ON pv.id_produto = p.id_produto
                AND pv.ativo = 1
            WHERE p.ativo = 1
        ) AS precos
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $precos = $stmt->fetch();

        $filtros['precoMin'] = (float) ($precos['precoMin'] ?? 0);
        $filtros['precoMax'] = (float) ($precos['precoMax'] ?? 0);

        return $filtros;
    }
}
