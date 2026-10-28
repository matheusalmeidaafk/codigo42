<?php

namespace App\Controller;

final class AdminUploadController
{
    public function imagem(): void
    {
        $file = $_FILES['imagem'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['erro' => 'Envie uma imagem de até 5 MB.']);
            return;
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($types[$mime])) {
            http_response_code(415);
            echo json_encode(['erro' => 'Formato permitido: JPG, PNG ou WebP.']);
            return;
        }

        $destination = dirname(__DIR__, 2) . '/uploads';
        if (!is_dir($destination) || !is_writable($destination)) {
            http_response_code(500);
            echo json_encode(['erro' => 'Pasta de imagens indisponível. Verifique o volume do Docker.']);
            return;
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $types[$mime];
        if (!move_uploaded_file($file['tmp_name'], $destination . '/' . $filename)) {
            http_response_code(500);
            echo json_encode(['erro' => 'Não foi possível salvar a imagem.']);
            return;
        }
        http_response_code(201);
        echo json_encode(['url' => '/assets/uploads/' . $filename]);
    }
}
