# projeto-de-software

Há 4 serviços configurados:

- app
- db na porta 5433:5432
- pgadmin na porta 8011
- ngix porta 8010

A aplicação sobe na porta 8010

### Instalação

Compile a imagem app
```bash
docker-compose build app
```

Suba os containers
```bash
docker-compose up -d
```

Instale as dependências
```bash
docker-compose exec app composer install
```

Abra a aplicação no browser com `localhost:8010`


### Configuração

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

- Execute a migration com `docker-compose exec app php artisan migrate`
