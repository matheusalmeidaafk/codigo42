<?php

use App\Controller\UsuarioController;

require_once __DIR__ . '/../../vendor/autoload.php';


header("Content-Type: application/json");

$controller = new UsuarioController();

$method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$uri = str_replace(
    "/backend/src/public",
    "",
    $uri
);

if ($method === "GET" && $uri === "/usuarios") {

    $controller->listar();

} elseif ($method === "POST" && $uri === "/usuarios") {

    $controller->criar();

} elseif ($method === "GET" && preg_match("#^/usuarios/(\d+)$#", $uri, $matches)) {

    //$controller->buscar((int) $matches[1]);

} else {

    http_response_code(404);

    echo json_encode([
        "erro" => "Rota não encontrada"
    ]);
}