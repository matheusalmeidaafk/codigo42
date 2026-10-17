<?php

namespace App\Model;

class Usuario {
    public ?int $id;
    public ?string $imagem_url;

    public string $nomeCompleto;
    
    public string $email;
    
    public string $telefone;

    public string $cpf;

    public string $senha;

    public bool $isAtivo;

    public function __construct(string $nomeCompleto, string $email, string $telefone, string $cpf, string $senha, bool $isAtivo, ?int $id = null, ?string $imagem_url = null) {
        $this->id = $id;
        $this->imagem_url = $imagem_url;
        $this->nomeCompleto = $nomeCompleto;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->isAtivo = $isAtivo;
    }
}