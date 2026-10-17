<?php

namespace App\Model;

class Produto {
    public ?int $id;
    public ?string $imagemUrl;
    public string $nome;
    public string $descricao;
    public float $preco;

    public bool $isAutoral;

    public bool $ativo;

    public function __construct(string $nome, string $descricao, float $preco, bool $ativo, bool $isAutoral, ?int $id = null, ?string $imagemUrl = null) {
        $this->id = $id;
        $this->imagemUrl = $imagemUrl;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->ativo = $ativo;
        $this->isAutoral = $isAutoral;
    }
}