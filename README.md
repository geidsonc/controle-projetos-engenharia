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
