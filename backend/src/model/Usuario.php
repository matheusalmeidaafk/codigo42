<?php

namespace App\Model;

class Usuario {
    public ?int $id;
    public string $nome;
    public string $email;

    public function __construct(string $nome, string $email, ?int $id = null)    {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
    }
}