<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controller\ProdutoController;
use App\Middleware\AuthMiddleware;
use App\Controller\AuthController;
use App\Controller\UsuarioController;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

$usuarioController = new UsuarioController();
$authController = new AuthController();
$produtoController = new ProdutoController();

$method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$uri = str_replace(
    "/codigo42/backend/src/public",
    "",
    $uri
);

if ($method === "GET" && $uri === "/usuarios") {
    exigirAutenticacao();
    $usuarioController->listar();

} elseif ($method === "POST" && $uri === "/cadastro") {

    $usuarioController->criar();

} elseif ($method === "DELETE" && preg_match("#^/usuarios/(\d+)$#", $uri, $matches)) {
    $usuario = exigirAutenticacao();

    $id = (int) $matches[1];

    if ($usuario->id != $id) {
        http_response_code(404);

        echo json_encode([
            "erro" => "Não pode deletar outros usuarios."
        ]);

        return;
    }


    $deletado = $usuarioController->deletar((int) $matches[1]);

    if (!$deletado) {
        http_response_code(404);

        echo json_encode([
            "erro" => "Usuário não encontrado."
        ]);

        return;
    }

    echo json_encode([
        "mensagem" => "Usuário removido com sucesso."
    ]);

} elseif ($method === "POST" && $uri === "/login") {

    $authController->login();

} elseif ($method === "POST" && $uri === "/produtos") {

    $produtoController->criarProduto();

} elseif ($method === "GET" && $uri === "/produtos") {

    $produtoController->listar();

} elseif ($method === "GET" && $uri === "/docs") {

    $docs = file_get_contents(__DIR__ . '/../../docs/index.html');
    
    header('Content-Type: text/html; charset=UTF-8');

    echo $docs;

} elseif ($method === "GET" && $uri === "/docs/openapi.yaml") {

    header('Content-Type: application/yaml; charset=UTF-8');

    readfile(__DIR__ . '/../../docs/openapi.yaml');

} else {

    http_response_code(404);

    echo json_encode([
        "erro" => "Rota não encontrada"
    ]);


}
function exigirAutenticacao(): object
{
    $middleware = new AuthMiddleware();

    return $middleware->autenticar();
}