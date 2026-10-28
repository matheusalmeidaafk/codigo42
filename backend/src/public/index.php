<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controller\AdminController;
use App\Controller\AdminResourceController;
use App\Controller\AdminUploadController;
use App\Controller\CategoriaController;
use App\Controller\ProdutoController;
use App\Middleware\AuthMiddleware;
use App\Controller\AuthController;
use App\Controller\UsuarioController;
use App\Controller\BannerController;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

/*
|--------------------------------------------------------------------------
| HEADERS / CORS
|--------------------------------------------------------------------------
*/

header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Origin: http://localhost:8081");

header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

header("Access-Control-Allow-Headers: Content-Type, Authorization");

/*
|--------------------------------------------------------------------------
| PREFLIGHT
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

$usuarioController = new UsuarioController();
$authController = new AuthController();
$produtoController = new ProdutoController();
$categoriaController = new CategoriaController();
$bannerController = new BannerController();
$adminController = new AdminController();
$adminResourceController = new AdminResourceController();

/*
|--------------------------------------------------------------------------
| ROTA
|--------------------------------------------------------------------------
*/

$method = $_SERVER["REQUEST_METHOD"];

$uri = parse_url(
    $_SERVER["REQUEST_URI"],
    PHP_URL_PATH
);

/*
 * Mantém compatibilidade com a estrutura antiga do XAMPP.
 *
 * No Docker, se chegar "/produtos", continuará "/produtos".
 */
$uri = str_replace(
    "/codigo42/backend/src/public",
    "",
    $uri
);

/*
|--------------------------------------------------------------------------
| USUÁRIOS
|--------------------------------------------------------------------------
*/

if ($method === "GET" && $uri === "/usuarios") {

    if(exigirAutenticacaoAdm()) $usuarioController->listar();

} elseif ($method === "POST" && $uri === "/cadastro") {

    $usuarioController->criar();

} elseif ($method === "GET" && $uri === "/usuarios/me") {
    $usuarioController->perfil(exigirUsuario());

} elseif ($method === "PUT" && $uri === "/usuarios/me") {
    $usuarioController->atualizarPerfil(exigirUsuario());

} elseif (
    $method === "DELETE"
    && preg_match("#^/usuarios/(\d+)$#", $uri, $matches)
) {

    $usuario = exigirUsuario();

    $id = (int) $matches[1];

    if ($usuario->id != $id) {

        http_response_code(403);

        echo json_encode([
            "erro" => "Não pode deletar outros usuários."
        ]);

        exit;
    }

    $deletado = $usuarioController->deletar($id);

    if (!$deletado) {

        http_response_code(404);

        echo json_encode([
            "erro" => "Usuário não encontrado."
        ]);

        exit;
    }

    echo json_encode([
        "mensagem" => "Usuário removido com sucesso."
    ]);

}


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
elseif ($method === "POST" && $uri === "/cadastroadm") {
    if (exigirAutenticacaoAdm()) $adminController->criar();

}


/*
|--------------------------------------------------------------------------
| AUTENTICAÇÃO
|--------------------------------------------------------------------------
*/

elseif ($method === "POST" && $uri === "/login") {

    $authController->login();

}

elseif($method === "POST" && $uri === "/loginadm"){
    $authController->loginAdm();
}

elseif ($method === 'POST' && $uri === '/admin/upload-imagem') {
    if (exigirAutenticacaoAdm()) (new AdminUploadController())->imagem();
}

elseif (preg_match('#^/admin/(produtos|categorias|banners|usuarios)(?:/(\d+))?$#', $uri, $matches)) {
    if (exigirAutenticacaoAdm()) {
        $adminResourceController->executar($matches[1], $method, isset($matches[2]) ? (int) $matches[2] : null);
    }
}

/*
|--------------------------------------------------------------------------
| PRODUTOS
|--------------------------------------------------------------------------
*/
elseif (
    $method === "GET"
    && $uri === "/produtos/filtros"
) {

    $produtoController->listarFiltros();

} elseif ($method === "GET" && $uri === "/produtos") {

    $produtoController->listar();

}

elseif ($method === "POST" && $uri === "/produtos") {

    if(exigirAutenticacaoAdm()) $produtoController->criarProduto();

} elseif ($method === "GET" && $uri === "/produtos") {

    $produtoController->listar();
}
elseif (
    $method === "GET"
    && preg_match("#^/produtos/([^/]+)$#", $uri, $matches)
) {
    $pesquisa = urldecode($matches[1]);

    $produtoController->pesquisar($pesquisa);

}



/*
|--------------------------------------------------------------------------
| CATEGORIAS
|--------------------------------------------------------------------------
*/

elseif ($method === 'GET' && $uri === '/categorias') {
    $categoriaController->listar();
}

/*
|--------------------------------------------------------------------------
| CATEGORIA
|--------------------------------------------------------------------------
*/

elseif ($method === "GET" && $uri === "/categorias") {
    $categoriaController->listar();
}
elseif ($method === "GET" && $uri === "/categoriasPai") {
    $categoriaController->getCategoriaPai();
}
elseif (
    $method === 'GET'
    && preg_match('#^/subcategoria/(\d+)$#', $uri, $matches)
) {
    $id = (int) $matches[1];

    $categoriaController->getSubcategoria($id);
}

/*
|--------------------------------------------------------------------------
| BANNERS
|--------------------------------------------------------------------------
*/

elseif ($method === "POST" && $uri === "/banners") {
    if (exigirAutenticacaoAdm()) $bannerController->criarBanner();

} elseif ($method === "GET" && $uri === "/banners") {

    $bannerController->listar();

} elseif (
    $method === "DELETE"
    && preg_match("#^/banners/(\d+)$#", $uri, $matches)
) {

    $id = (int) $matches[1];

    if (exigirAutenticacaoAdm()) $bannerController->deletar($id);

}

/*
|--------------------------------------------------------------------------
| DOCUMENTAÇÃO
|--------------------------------------------------------------------------
*/

elseif ($method === "GET" && $uri === "/docs") {

    $docs = file_get_contents(
        __DIR__ . '/../../docs/index.html'
    );

    header('Content-Type: text/html; charset=UTF-8');

    echo $docs;

} elseif (
    $method === "GET"
    && $uri === "/docs/openapi.yaml"
) {

    header(
        'Content-Type: application/yaml; charset=UTF-8'
    );

    readfile(
        __DIR__ . '/../../docs/openapi.yaml'
    );

}

/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
*/

else {

    http_response_code(404);

    echo json_encode([
        "erro" => "Rota não encontrada"
    ]);

}


/*
|--------------------------------------------------------------------------
| AUTENTICAÇÃO
|--------------------------------------------------------------------------
*/

function exigirAutenticacao(): object
{
    $middleware = new AuthMiddleware();

    return $middleware->autenticar();
}

function exigirAutenticacaoAdm(): bool
{
    $middleware = new AuthMiddleware();

    return $middleware->autenticarAdm();
}

function exigirUsuario(): object
{
    $usuario = exigirAutenticacao();
    if (($usuario->tipo ?? null) !== 'USUARIO') {
        http_response_code(403);
        echo json_encode(['erro' => 'Acesso exclusivo para usuários.']);
        exit;
    }
    return $usuario;
}
