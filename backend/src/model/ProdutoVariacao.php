<?php

namespace App\Model;

class ProdutoVariacao {
    public ?int $id;
    public int $id_produto;
    public ?string $tamanho;
    public ?string $cor;
    public ?float $preco;
    public bool $ativo;

    public function __construct(
        ?int $id,
        ?int $id_produto,
        ?string $tamanho,
        ?string $cor,
        ?float $preco,
        bool $ativo
    ) {
        $this->id = $id;
        $this->id_produto = $id_produto;
        $this->tamanho = $tamanho;
        $this->cor = $cor;
        $this->preco = $preco;
        $this->ativo = $ativo;
    }
}