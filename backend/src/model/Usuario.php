<?php

namespace App\Model;

class Usuario {
    public ?int $id;
    public ?string $imgperfilurl;

    public string $nomeCompleto;
    
    public string $email;
    
    public string $telefone;

    public string $cpf;

    public string $senha;

    public bool $isAtivo;

    public function __construct(?int $id = null, ?string $imgperfilurl = null, string $nomeCompleto, string $email, string $telefone, string $cpf, string $senha, bool $isAtivo) {
        $this->id = $id;
        $this->imgperfilurl = $imgperfilurl;
        $this->nomeCompleto = $nomeCompleto;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->isAtivo = $isAtivo;
    }
}