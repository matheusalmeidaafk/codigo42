# Código 42

Projeto de ecommerce desenvolvido em PHP.

O backend é executado utilizando Docker com PHP 8.3 + Apache e utiliza um banco de dados MySQL hospedado na Aiven.

## Requisitos

Para executar o projeto é necessário ter instalado:

- Docker
- Docker Compose

Não é necessário instalar PHP, Apache, Composer ou MySQL localmente.

## Configuração do ambiente

O projeto utiliza o arquivo:

```text
backend/.env
```

Use o arquivo:

```text
backend/.env.example
```

como referência para criar/configurar o `.env`.

Exemplo:

```env
JWT_SECRET=

DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Preencha os valores com as credenciais válidas do banco Aiven.

Também é necessário manter o certificado SSL da Aiven em:

```text
backend/certificates/ca.pem
```

> Não coloque senhas ou outras credenciais diretamente no README.

## Inicializando o projeto

Na raiz do projeto, onde estão `Dockerfile` e `docker-compose.yml`, execute:

```bash
docker compose up -d --build
```

Esse comando:

1. constrói a imagem Docker;
2. instala/configura PHP e Apache;
3. instala as extensões necessárias;
4. disponibiliza o Composer;
5. instala as dependências PHP;
6. inicia o container da aplicação.

Depois disso, a API estará disponível em:

```text
http://localhost:8080
```

## Testando a API

Para testar a listagem de produtos:

```bash
curl http://localhost:8080/produtos
```

Também é possível acessar pelo navegador:

```text
http://localhost:8080/produtos
```

## Verificando os containers

```bash
docker compose ps
```

## Visualizando os logs

```bash
docker compose logs -f app
```

Para sair da visualização dos logs:

```text
Ctrl + C
```

Isso não encerra o container.

## Parando o projeto

```bash
docker compose down
```

## Iniciando novamente

Se a imagem já foi construída e não houve alteração no Dockerfile:

```bash
docker compose up -d
```

## Reconstruindo a imagem

Caso haja alteração no Dockerfile ou seja necessário reconstruir completamente a aplicação:

```bash
docker compose up -d --build
```

## Reiniciando somente a aplicação

```bash
docker compose restart app
```

## Composer

O Composer está disponível dentro do container.

Para instalar as dependências:

```bash
docker compose exec app composer install
```

Caso sejam alterados namespaces ou o `composer.json`, regenere o autoload:

```bash
docker compose exec app composer dump-autoload
```

## Banco de dados

O projeto NÃO utiliza um container MySQL local.

A aplicação conecta diretamente ao banco MySQL hospedado na Aiven.

As configurações são obtidas através das variáveis presentes em:

```text
backend/.env
```

A conexão SSL utiliza:

```text
backend/certificates/ca.pem
```

## Estrutura principal

```text
backend/
├── certificates/
│   └── ca.pem
├── src/
│   ├── config/
│   ├── controller/
│   ├── middleware/
│   ├── model/
│   ├── public/
│   ├── security/
│   └── service/
├── .env
├── .env.example
└── composer.json

docker-compose.yml
Dockerfile
README.md
```

## Desenvolvimento

A pasta local:

```text
./backend
```

é montada dentro do container em:

```text
/var/www/html
```

Por isso, durante o desenvolvimento, alterações feitas nos arquivos PHP normalmente aparecem no container sem precisar reconstruir a imagem.

Caso seja alterada a configuração do Composer/autoload, execute:

```bash
docker compose exec app composer dump-autoload
```

## Comandos rápidos

### Subir

```bash
docker compose up -d --build
```

### Ver status

```bash
docker compose ps
```

### Ver logs

```bash
docker compose logs -f app
```

### Testar produtos

```bash
curl http://localhost:8080/produtos
```

### Atualizar autoload

```bash
docker compose exec app composer dump-autoload
```

### Parar

```bash
docker compose down
```