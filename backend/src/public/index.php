<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Middleware\AuthMiddleware;
use App\Controller\AuthController;
use App\Controller\UsuarioController;
use Dotenv\Dotenv;


function exigirAutenticacao(): object {
    $middleware = new AuthMiddleware();

    return $middleware->autenticar();
}


$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

header("Content-Type: application/json");

$usuarioController = new UsuarioController();
$authController = new AuthController();

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

} elseif ($method === "GET" && preg_match("#^/usuarios/(\d+)$#", $uri, $matches)) {

    //$controller->buscar((int) $matches[1]);

} elseif ($method === "POST" && $uri === "/login") {

    $authController->login();

} else {

    http_response_code(404);

    echo json_encode([
        "erro" => "Rota não encontrada"
    ]);
}