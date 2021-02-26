# Projeto de sistema para controle de projetos em um departamentode engenharia
Este projeto foi construído no curso de pós-gradução em Engenharia de Software como resultado das disciplinas de Engenharia de Requisitos e Análise de Projeto de Software.

O sistema analisado e construído visa auxiliar o um chefe de departamento de engenharia na gestão, supervisão e distribuição de projetos de convênios. Irá armazenar os pareceres emitidos pelos técnicos responsáveis durante a execução das atividades de análise e acompanhamento dos projetos de engenharia. 


### Requisitos

Tenha instalado em seu ambiente o Docker e o docker-compose
Guia de instalação:
- Docker: [Guia de instalação do docker](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-20-04-pt)
- docker-compose: [Guia de instalação do docker-compose](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-20-04-pt)


### Geral

O sistema utiliza o framework Laravel, servidor ngix e base de dados postgres.

Há 4 serviços pré-configurados para o docker:

- app - Imagem Ubuntu com `php`, `npm` e outras bibliotecas para executar a aplicação
- db - Imagem Ubuntu com `postgres` configurado
- pgadmin - Imagem com cliente pgadmin disponível na porta `8011`
- ngix - Imagem com o serviço HTTP Server `ngix` configurado disponível na porta `8010`


### Instalação

#### Passo 1

Faça um clone desse projeto.
`git clone git@github.com:geidsonc/projeto-de-software.git`

#### Passo 2

Compile a imagem docker app.
Na raiz do projeto execute.
```bash
docker-compose build app
```

#### Passo 3

Suba os containers com o comando
```bash
docker-compose up -d
```

#### Passo 4
Instale as dependências
```bash
docker-compose exec app composer install
```

```bash
docker-compose exec app npm i
```

#### Passo 5
Compile o front-end
```bash
docker-compose exec app npm i
```

#### Passo 6

Faça a configuração do projeto

- Faça a cópia do `.env.example` para `.env`
- Gere a chave da aplicação `docker-compose exec app php artisan key:g`
- Crie um banco dados no postgres. Pode usar o pgadmin em `localhost:8011`. Para acessar use `user@mail.com`, senha `12345`.
- Configure as variaveis do banco de dados no `.env`
```
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=nome-do-banco
DB_USERNAME=postgres
DB_PASSWORD=12345
```

- Execute a migração para criar as tabelas e popular a base de dados
```
docker-compose exec app php artisan migrate:fresh --seed
```

Abra a aplicação no browser com `localhost:8010`
