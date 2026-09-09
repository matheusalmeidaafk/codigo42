<?php

namespace App\Service;

use App\Config\OnlineDB;
use App\Model\Banner;
use Exception;
use PDO;

class BannerService
{
    private PDO $db;

    public function __construct()
    {
        $database = new OnlineDB();
        $this->db = $database->conectar();
    }

    public function criar(
        string $bannerUrl,
        ?string $linkUrl,
        bool $ativo = true
    ): Banner {

        if (empty(trim($bannerUrl))) {
            throw new Exception("URL do banner é obrigatória.");
        }

        $sql = "
            INSERT INTO banner (
                banner_url,
                link_url,
                ativo
            ) VALUES (?, ?, ?)
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $bannerUrl,
            $linkUrl,
            $ativo
        ]);

        $id = (int) $this->db->lastInsertId();

        return new Banner(
            $id,
            $bannerUrl,
            $linkUrl,
            $ativo
        );
    }

    public function listar(): array
    {
        $sql = "
            SELECT
                id_banner AS id,
                banner_url AS bannerUrl,
                link_url AS linkUrl,
                ativo
            FROM banner
            WHERE ativo = TRUE
            ORDER BY id_banner DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?Banner
    {
        $sql = "
            SELECT
                id_banner AS id,
                banner_url AS bannerUrl,
                link_url AS linkUrl,
                ativo
            FROM banner
            WHERE id_banner = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$banner) {
            return null;
        }

        return new Banner(
            (int) $banner["id"],
            $banner["bannerUrl"],
            $banner["linkUrl"],
            (bool) $banner["ativo"]
        );
    }

    public function deletar(int $id): void
{
    $banner = $this->buscarPorId($id);

    if (!$banner) {
        throw new Exception("Banner não encontrado.");
    }

    $sql = "
        UPDATE banner
        SET ativo = FALSE
        WHERE id_banner = ?
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
}

}
