<?php

namespace App\Model;

class Produto {
    public ?int $id;
    public ?string $imagemUrl;
    public string $nome;
    public string $descricao;
    public string $tamanho;
    public float $preco;
    public int $estoque;
    public bool $ativo;

    public function __construct(?int $id = null, ?string $imagemUrl = null, string $nome, string $descricao, string $tamanho, float $preco, int $estoque, bool $ativo) {
        $this->id = $id;
        $this->imagemUrl = $imagemUrl;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->tamanho = $tamanho;
        $this->preco = $preco;
        $this->estoque = $estoque;
        $this->ativo = $ativo;
    }
}