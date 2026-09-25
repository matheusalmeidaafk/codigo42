## Documentação do Projeto

Projeto desenvolvido em PHP, executado no ambiendo do Docker com PHP 8.3
A aplicação utiliza MySQL hospedado na Aiven e Bootstrap 5.3

Esta documentação reúne as instruções necessárias para:
configurar, executar e desenvolver o projeto.

## Sumário

Acesso rápido:

- [Configuração do ambiente](#3-configuração-do-ambiente)
- [Inicializando o projeto](#4-inicializando-o-projeto)
- [Testando a API](#5-testando-a-api)
- [Gerenciamento dos containers](#6-gerenciamento-dos-containers)
- [Estrutura principal do backend](#9-estrutura-principal-do-backend)
- [Estrutura de pastas do frontend](#11-estrutura-de-pastas)
- [Padrão de nomenclatura](#12-padrão-de-nomenclatura)
- [Bootstrap](#13-bootstrap)
- [Componentes](#15-componentes)
- [Padrão de branches](#padrão-de-branches)
- [Comandos rápidos](#18-comandos-rápidos)

---

## 1. Tecnologias utilizadas

- PHP 8.3
- Docker
- MySQL
- Aiven
- Bootstrap 5.3

---

## 2. Requisitos

Para executar o projeto localmente, é necessário ter instalado:

- Docker Desktop

Não é necessário instalar PHP, Apache, Composer ou MySQL diretamente na máquina, pois os componentes necessários para a aplicação são disponibilizados pelo ambiente do Docker.

---

## 3. Configuração do ambiente

O projeto utiliza o arquivo:

```text
backend/.env
```

Use o arquivo abaixo como referência para criar ou configurar o `.env`:

```text
backend/.env.example
```

Exemplo de configuração:

```env
JWT_SECRET=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Preencha as variáveis com as credenciais válidas do ambiente utilizado pelo projeto ou baixe o .env diretamente no Drive fornecido.

### Certificado SSL da Aiven

O certificado SSL deve permanecer em:

```text
backend/certificates/ca.pem
```

### Segurança das credenciais

- Não incluir senhas, tokens ou outras credenciais diretamente no README ou em arquivos versionados.
- O arquivo `.env` deve permanecer fora do controle de versão, não deve estar no comit.
- O `.env.example` deve conter apenas os nomes das variáveis necessárias, sem valores sensíveis.
- Antes de realizar um commit, confirme que nenhuma credencial foi adicionada acidentalmente ao código.

---

## 4. Inicializando o projeto

Na raiz do projeto, onde estão `Dockerfile` e `docker-compose.yml`, execute:

```bash
docker compose up -d --build
```

Esse comando faz:

1. constrói a imagem Docker;
2. configura PHP e Apache;
3. instala as extensões necessárias;
4. disponibiliza o Composer;
5. instala as dependências PHP;
6. inicia o container da aplicação.

Após a inicialização, a aplicação estará disponível em:

- BackEnd

```text
http://localhost:8080
```

- FrontEnd

```text
http://localhost:8081
```

---

## 5. Testando a API

Para testar a listagem de produtos pelo terminal:

```bash
curl http://localhost:8080/produtos
```

Também é possível acessar a rota pelo navegador:

```text
http://localhost:8080/produtos
```

---

## 6. Gerenciamento dos containers

### Verificar o status

```bash
docker compose ps
```

### Visualizar os logs da aplicação

```bash
docker compose logs -f app
```

Para sair, utilizar:

```text
Ctrl + C
```

Esse comando interrompe apenas a visualização dos logs e não encerra o container.

### Parar o projeto

```bash
docker compose down
```

### Iniciar novamente

Se a imagem já foi construída e não houve alteração no `Dockerfile` ou seja voce já executou o comando de Build:

```bash
docker compose up -d
```

### Reconstruir a imagem

Utilize quando houver alterações no `Dockerfile` ou quando for necessário reconstruir completamente o ambiente:

```bash
docker compose up -d --build
```

### Reiniciar somente a aplicação

```bash
docker compose restart app
```

---

## 7. Composer

O Composer está disponível dentro do container da aplicação.

### Instalar dependências

```bash
docker compose exec app composer install
```

### Atualizar o autoload

Caso sejam alterados namespaces, classes ou o arquivo `composer.json`, execute:

```bash
docker compose exec app composer dump-autoload
```

---

## 8. Banco de dados

O projeto não utiliza um container MySQL local.

A aplicação conecta diretamente ao banco MySQL hospedado na Aiven. As configurações de conexão são obtidas por meio das variáveis presentes em:

```text
backend/.env
```

A conexão SSL utiliza o certificado:

```text
backend/certificates/ca.pem
```

Credenciais do banco não devem ser adicionadas ao código-fonte ou à documentação versionada.

---

## 9. Estrutura principal do backend

```text
backend/
  - certificates/
    - ca.pem

  - src/
    - config/
    - controller/
    - middleware/
    - model/
    - public/
    - security/
    - service/

  - .env
  - .env.example
  - composer.json

- docker-compose.yml
- Dockerfile
- README.md
```

### Responsabilidade das principais pastas

- `config/`: configurações da aplicação e integração com serviços externos.
- `controller/`: tratamento das requisições e coordenação do fluxo entre entrada, serviços e resposta.
- `middleware/`: validações e comportamentos executados no fluxo das requisições.
- `model/`: representação e manipulação das entidades e dados da aplicação.
- `public/`: arquivos ou pontos de entrada disponibilizados publicamente pela aplicação.
- `security/`: recursos relacionados a autenticação, autorização e segurança.
- `service/`: regras de negócio e operações reutilizáveis da aplicação.

---

## 10. Desenvolvimento local

A pasta local:

```text
./backend
```

é montada dentro do container em:

```text
/var/www/html
```

Durante o desenvolvimento, alterações realizadas nos arquivos PHP normalmente ficam disponíveis no container sem a necessidade de reconstruir a imagem.

Caso sejam alterados namespaces, classes ou configurações do autoload, execute:

```bash
docker compose exec app composer dump-autoload
```

Reconstrua a imagem apenas quando a alteração realmente afetar a configuração do ambiente, como mudanças no `Dockerfile` ou em dependências de sistema.

---

# Padrões do Frontend

## 11. Estrutura de pastas

A organização do frontend deve seguir a separação abaixo:

```text
- pages/
- components/
- assets/
  - css/
  - js/
  - images/
```

### Responsabilidades

- `pages/`: páginas do sistema.
- `components/`: componentes visuais reutilizáveis nao use logica dentro do component.
- `assets/css/`: estilos personalizados do projeto.
- `assets/js/`: arquivos JavaScript.
- `assets/images/`: imagens utilizadas pela interface, preferencialmente organizadas por página ou contexto quando isso facilitar a manutenção.

Evite misturar arquivos com responsabilidades diferentes dentro da mesma pasta.

---

## 12. Padrão de nomenclatura

### camelCase

Utilizar `camelCase` para:

- variáveis;
- funções;
- componentes do frontend;
- pastas;
- arquivos do frontend.

Exemplos:

```php
$nomeProduto;
$precoProduto;
$quantidadeEstoque;

calcularTotal();
adicionarProduto();
```

Exemplos de arquivos:

```text
productCard.php
navbar.php
footer.php
```

### PascalCase

Utilizar `PascalCase` para:

- classes;
- arquivos do backend quando representarem classes.

Exemplo:

```php
class Produto
{
}
```

## 13. Bootstrap

O projeto utiliza Bootstrap 5.3 para a estruturação visual da interface.

Documentação oficial:

```text
https://getbootstrap.com/docs/5.3/getting-started/introduction/
```

### Ordem de prioridade

1. Verifique primeiro se o Bootstrap já oferece uma classe ou componente adequado para a necessidade.
2. Utilize CSS próprio apenas para customizações específicas que não sejam atendidas adequadamente pelo Bootstrap.

### Exemplo

Prefira utilizar utilitários existentes:

```html
<div class="d-flex justify-content-between align-items-center gap-3"></div>
```

em vez de criar uma nova classe CSS apenas para reproduzir o mesmo comportamento.

---

## 14. CSS personalizado

Quando uma customização própria for necessária:

- mantenha a regra no arquivo CSS correspondente à página ou ao componente;
- utilize nomes de classe claros e relacionados à finalidade do elemento;
- evite sobrescrever classes globais do Bootstrap sem necessidade;
- evite `!important`, exceto quando houver uma justificativa técnica clara;
- não duplique regras que já existem em outro arquivo do projeto.

---

## 15. Componentes

Elementos reutilizados em diferentes páginas devem ser implementados como componentes.

Uma regra prática para decidir se algo deve virar componente é verificar se o mesmo elemento provavelmente será utilizado em duas ou mais partes do sistema.

Exemplos comuns:

```text
navbar.php
footer.php
productCard.php
```

### Responsabilidade do componente

Componentes visuais devem receber os dados necessários e cuidar da apresentação desses dados.

Não coloque regras de negócio dentro de componentes visuais.

Exemplo de responsabilidade inadequada para `productCard.php`:

```text
Se o estoque for menor que 5, aplicar automaticamente um desconto de R$ 10.
```

Essa decisão pertence à camada responsável pela regra de negócio. O componente deve apenas apresentar o preço, estoque, desconto ou estado que receber.

---

## 16. Separação de responsabilidades

Para manter o projeto organizado:

- páginas devem coordenar a composição da interface;
- componentes devem cuidar de partes reutilizáveis da apresentação;
- CSS deve cuidar da aparência;
- JavaScript deve cuidar do comportamento executado no navegador;
- regras de negócio devem permanecer fora dos componentes visuais;
- acesso ao banco de dados não deve ser realizado diretamente por componentes da interface.

---

## 17. Boas práticas de colaboração

### Padrão de branches

Cada atividade deve ser desenvolvida em uma branch própria.

O nome da branch deve seguir as informações da atividade cadastrada no projeto.

---

Exemplo de atividade:

```text
BLG02 - Sprint03 - Tarefa01 - Construir componente Header - frontend
```

Nome da branch:

```text
BLG02_Sprint03_Tarefa01_ComponenteHeader-Frontend
```

---

Outro exemplo:

```text
BLG02 - Sprint03 - Tarefa02 - Criar página de produtos - frontend
```

Nome da branch:

```text
BLG02_Sprint03_Tarefa02_PaginaProdutos-Frontend
```

---

Exemplo de atividade de backend:

```text
BLG02 - Sprint03 - Tarefa03 - Criar rota de produtos - backend
```

Nome da branch:

```text
BLG02_Sprint03_Tarefa03_RotaProdutos-Backend
```

---

Regras para nomeação:

- cada atividade deve possuir sua própria branch;
- utilizar o código do backlog, sprint e tarefa correspondente;
- após a identificação da tarefa, adicionar um nome curto que descreva o que será desenvolvido;
- informar ao final se a atividade pertence ao `Frontend` ou `Backend`;
- não utilizar espaços no nome da branch;
- evitar nomes genéricos como `teste`, `alteracao`, `ajuste` ou `novaBranch`;
- não desenvolver diretamente na branch principal;
- antes de iniciar uma nova atividade, atualizar o projeto local com a versão mais recente da branch principal.

### Antes de abrir ou concluir uma integração

Verifique:

- se o projeto inicia corretamente;
- se não há credenciais versionadas;
- se não existem arquivos temporários ou de teste desnecessários;
- se a nomenclatura segue o padrão do projeto;
- se o código novo não duplica componentes já existentes;
- se a alteração não introduz regra de negócio em componentes visuais;
- se páginas afetadas continuam funcionando em diferentes tamanhos de tela.

---

## 18. Comandos rápidos

### Subir o projeto

```bash
docker compose up -d --build
```

### Verificar o status

```bash
docker compose ps
```

### Visualizar logs

```bash
docker compose logs -f app
```

### Testar a rota de produtos

```bash
curl http://localhost:8080/produtos
```

### Reiniciar a aplicação

```bash
docker compose restart app
```

### Parar o projeto

```bash
docker compose down
```

## 19. Observações finais

Esta documentação deve acompanhar a evolução do projeto. Sempre que houver mudança relevante na estrutura de diretórios, configuração do ambiente, dependências, comandos de execução ou padrões adotados pela equipe, o documento deve ser atualizado.
