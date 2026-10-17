<?php

namespace App\Model;

class Admin {

    public ?int $id;

    public string $email;

    public string $senha;

    public bool $ativo;

    public function __construct(string $email, string $senha, ?bool $ativo = true, ?int $id = null) {
        $this->id = $id;
        $this->email = $email;
        $this->senha = $senha;
        $this->ativo = $ativo;
    }
}