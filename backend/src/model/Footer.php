<?php

namespace App\Model;

class Footer {
    public ?int $id;
    public string $logoUrl;
    public string $descricao;

    public function __construct(string $logoUrl, string $descricao, ?int $id = null) {
        $this->id = $id;
        $this->logoUrl = $logoUrl;
        $this->descricao = $descricao;
    }
}
