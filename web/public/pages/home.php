<?php

require_once __DIR__ . '/../../components/carrosselProdutos.php';

$produtos = [
    [
        'nome' => 'Camiseta Código 42',
        'estrelas' => 5,
        'preco' => 89.90,
        'imagem_url' => 'https://placehold.co/600x600?text=Camiseta'
    ],
    [
        'nome' => 'Caneca Código 42',
        'estrelas' => 4,
        'preco' => 49.90,
        'imagem_url' => 'https://placehold.co/600x600?text=Caneca'
    ],
    [
        'nome' => 'Moletom Código 42',
        'estrelas' => 5,
        'preco' => 159.90,
        'imagem_url' => 'https://placehold.co/600x600?text=Moletom'
    ],
    [
        'nome' => 'Boné Código 42',
        'estrelas' => 3,
        'preco' => 69.90,
        'imagem_url' => 'https://placehold.co/600x600?text=Bone'
    ],
    [
        'nome' => 'Adesivo Código 42',
        'estrelas' => 4,
        'preco' => 9.90,
        'imagem_url' => 'https://placehold.co/600x600?text=Adesivo'
    ],
    [
        'nome' => 'Mousepad Código 42',
        'estrelas' => 5,
        'preco' => 39.90,
        'imagem_url' => 'https://placehold.co/600x600?text=Mousepad'
    ],
];

renderCarrosselProdutos($produtos);
