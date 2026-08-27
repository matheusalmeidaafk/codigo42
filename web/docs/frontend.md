# Padrões do Frontend

## Estrutura de pastas

- `pages/` -> páginas do sistema
- `components/` -> componentes
- `assets/css/` -> estilos
- `assets/js/` -> JavaScript
- `assets/images/` -> imagens separado pelas paginas de preferencia

## Nomenclatura

- Variáveis: camelCase
    - $nomeProduto;
    - $precoProduto;
    - $quantidadeEstoque;

- Funções: camelCase
    - calcularTotal();
    - adicionarProduto();

- Componentes: camelCase
    - productCard.php
    - navbar.php
    - footer.php

- Classes: PascalCase
    - class Produto {}

## Framework

O projeto utiliza Bootstrap para estruturação visual,

## Componentes

Elementos reutilizados em diferentes páginas devem ser implementados como componentes.

dica se você acha que deve uo não ser um component pense: È algo que você acha que ira reutilizar +2 vezes?

Não colocar regra de negócio dentro dos componentes visuais.

Por exemplo, productCard.php apresenta um produto. Ele não deveria decidir coisas como:
"Se estoque for menor que 5, descontar R$ 10."

## Bootstrap

1° Bootstrap primeiro = Se o Bootstrap já possui uma solução adequada, utiliza a solução existente.

2° De segundo = fazer um .css próprio para a customização especifica.

## 