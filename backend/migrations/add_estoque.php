<?php

// Execute uma vez (ou novamente com segurança):
// docker compose exec app php /var/www/html/migrations/add_estoque.php
require_once dirname(__DIR__) . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
$db = (new App\Config\OnlineDB())->conectar();
$query = $db->query("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'produto_variacao' AND COLUMN_NAME = 'estoque'");

if ((int) $query->fetchColumn() === 0) {
    $db->exec('ALTER TABLE produto_variacao ADD COLUMN estoque INT NOT NULL DEFAULT 0');
    echo "Coluna estoque criada com sucesso.\n";
} else {
    echo "Coluna estoque já existe. Nenhuma alteração necessária.\n";
}
