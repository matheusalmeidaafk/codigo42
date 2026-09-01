# Padrões do Frontend

## Estrutura de pastas

- `pages/` -> páginas do sistema
- `components/` -> componentes
- `assets/css/` -> estilos
- `assets/js/` -> JavaScript
- `assets/images/` -> imagens separado pelas paginas de preferencia

## Nomenclatura

camelCase EX:
    - $nomeProduto;
    - $precoProduto;
    - $quantidadeEstoque;
    - calcularTotal();
    - adicionarProduto();
    - productCard.php
    - navbar.php
    - footer.php

PascalCase EX:
    - class Produto {}


- Variáveis: camelCase

- Funções: camelCase

- Componentes: camelCase

- Classes: PascalCase

- Pastas: camelCase

- arquivos: 
    - back: PascalCase
    - front: camelCase

## Framework

O projeto utiliza Bootstrap 5.3 para estruturação visual,

link Bootstrap:
https://getbootstrap.com/docs/5.3/getting-started/introduction/

- Bootstrap

    - 1° Bootstrap primeiro = Se o Bootstrap já possui uma solução adequada, utiliza a solução existente.

    - 2° De segundo = fazer um .css próprio para a customização especifica.


## Componentes

Elementos reutilizados em diferentes páginas devem ser implementados como componentes.

dica se você acha que deve uo não ser um component pense: È algo que você acha que ira reutilizar +2 vezes?

Não colocar regra de negócio dentro dos componentes visuais.

Por exemplo, productCard.php apresenta um produto. Ele não deveria decidir coisas como:
"Se estoque for menor que 5, descontar R$ 10."

## 