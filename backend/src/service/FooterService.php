<?php

namespace App\Service;

use App\Config\OnlineDB;
use App\Model\Footer;
use Exception;
use PDO;

class FooterService
{
    private PDO $db;

    public function __construct()
    {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    public function obter(): ?Footer
    {
        $sql = "SELECT id, logo_url, descricao FROM footer ORDER BY id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $linha = $stmt->fetch();

        if (!$linha) {
            return null;
        }

        return new Footer(
            $linha["logo_url"],
            $linha["descricao"],
            (int) $linha["id"]
        );
    }

    public function salvar(?string $logoUrl, ?string $descricao): Footer
    {
        $logoUrl = trim($logoUrl ?? "");
        $descricao = trim($descricao ?? "");

        if ($logoUrl === "") {
            throw new Exception("Logo do footer é obrigatória.");
        }
        if (mb_strlen($logoUrl) > 255) {
            throw new Exception("URL da logo deve ter no máximo 255 caracteres.");
        }
        if ($descricao === "") {
            throw new Exception("Descrição do footer é obrigatória.");
        }
        if (mb_strlen($descricao) > 500) {
            throw new Exception("Descrição do footer deve ter no máximo 500 caracteres.");
        }

        $existente = $this->obter();

        if ($existente === null) {
            $sql = "INSERT INTO footer (logo_url, descricao) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$logoUrl, $descricao]);

            $id = (int) $this->db->lastInsertId();
        } else {
            $sql = "UPDATE footer SET logo_url = ?, descricao = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$logoUrl, $descricao, $existente->id]);

            $id = $existente->id;
        }

        return new Footer($logoUrl, $descricao, $id);
    }
}
