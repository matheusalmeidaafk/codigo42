<?php

namespace App\Model;

class Categoria {
    public ?int $id;

    public string $nome;

    public ?int $idCategoriaPai;

    public function __construct(string $nome, ?int $id = null, ?int $idCategoriaPai = null) {
        $this->id = $id;
        $this->$nome = $nome;
        $this->idCategoriaPai = $idCategoriaPai;
    }
}