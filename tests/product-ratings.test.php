<?php
// php -d extension=pdo_sqlite tests/product-ratings.test.php
require __DIR__ . '/../backend/src/service/ProdutoService.php';
require __DIR__ . '/../web/components/cardProduto.php';
$db = new PDO('sqlite::memory:');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('CREATE TABLE produto (id_produto INTEGER, nome TEXT, preco REAL, ativo INTEGER, is_autoral INTEGER);
CREATE TABLE avaliacao_produto (id_produto INTEGER, estrelas INTEGER);
CREATE TABLE produto_variacao (id_variacao INTEGER, id_produto INTEGER, tamanho TEXT, cor TEXT, ativo INTEGER, estoque INTEGER);
CREATE TABLE imagem_variacao (id_variacao INTEGER, imagem_url TEXT);
CREATE TABLE desconto (id_produto INTEGER, porcentagem_desconto REAL, ativo INTEGER);
INSERT INTO produto VALUES (1,"Camiseta",100,1,0),(2,"Caneca",40,1,0);
INSERT INTO avaliacao_produto VALUES (1,4),(1,5);
INSERT INTO produto_variacao VALUES (11,1,"P","Preto",1,4),(12,1,"M","Preto",1,8);
INSERT INTO imagem_variacao VALUES (11,"foto1.png"),(11,"foto2.png");
INSERT INTO desconto VALUES (1,20,1);');
$class = new ReflectionClass(App\Service\ProdutoService::class);
$service = $class->newInstanceWithoutConstructor();
$class->getProperty('db')->setValue($service, $db);
function check($condition, $message) { if (!$condition) throw new RuntimeException($message); }
$rows = $service->filtrarCategoria(null, null, null, null);
foreach ($rows as $p) {
    if ((int) $p['id_produto'] === 1) {
        check($p['estrelas'] === 4.5, 'Média fracionada perdida');
        check($p['total_avaliacoes'] === 2, 'Fotos/variações duplicaram avaliações');
        check((float) $p['preco_final'] === 80.0, 'Desconto alterado');
    } else check($p['estrelas'] === 0.0 && $p['total_avaliacoes'] === 0, 'Produto sem avaliações');
}
$filtered = $service->filtrarCategoria(null,null,null,null,['M']);
check(count($filtered) > 0, 'Tamanho disponível omitido por escolher primeira variação');
check($filtered[0]['estrelas'] === 4.5, 'Filtro perdeu avaliação');
foreach ([0.0, 4.5, 5.0] as $rating) {
    ob_start(); CardProduto(1,'Produto <teste>', $rating,100,80,20,'foto.png'); $html=ob_get_clean();
    check(str_contains($html, 'width: ' . ($rating * 20) . '%'), 'Preenchimento incorreto');
    check(str_contains($html, 'Produto &lt;teste&gt;'), 'Título sem escape');
    if (!$rating) check(str_contains($html, 'Sem avaliações'), 'Estado vazio ausente');
}
echo "OK: média 4,5; sem avaliações; fotos múltiplas; filtro por tamanho; desconto; renderização e escape.\n";
