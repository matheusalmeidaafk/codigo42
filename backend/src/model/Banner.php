<?php

namespace App\Model;

class Banner
{
    public ?int $id;
    public string $bannerUrl;
    public ?string $linkUrl;
    public bool $ativo;

    public function __construct(
        ?int $id = null,
        string $bannerUrl,
        ?string $linkUrl = null,
        bool $ativo = true
    ) {
        $this->id = $id;
        $this->bannerUrl = $bannerUrl;
        $this->linkUrl = $linkUrl;
        $this->ativo = $ativo;
    }
}
