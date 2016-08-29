# GeekPot API

API Restfull feita com Laravel, OAuth2 e PHP 7.0+

## Instalação
Para fazer a instalação, basta rodar no terminal o comando

`composer install`

O Dump do banco de dados está em: /bd.sql

Para configurar o acesso a base, basta configurar as diretivas com o prefixo DB_ no arquivo .env

## Request
Para fazer as requests com o token de autorização, basta inserir no cabeçalho da requisição
```
key: Authorization
value: Bearer TOKEN
```

## Cadastro
Acessar via Browser: /cadastro, para abertura de formulário de cadastro

## Endpoints da API

#### Lookup
`GET /api/lookup`

#### Login do admin (validade do token: 5 minutos)
`POST /oauth/access_token`
```
Params:
    grant_type: password,
    client_id: 6e1ftdtwr80ty9zfkqzj,
    client_secret: 9vpczvqmkndob6doqjqa
    username: H5mee8TU3muDD7pbhwxA
    password: qbVhDOAYP1VlfOmvE5TT
```

#### Login (validade do token: 5 minutos)
`POST /oauth/access_token`
```
Params:
    grant_type: password,
    client_id: 6e1ftdtwr80ty9zfkqzj,
    client_secret: 9vpczvqmkndob6doqjqa
    username: API_KEY
    password: API_SECRET
```

#### Refresh Token  (validade do token: 15 minutos)
`POST /oauth/access_token`
```
Params:
    grant_type: refresh_token,
    client_id: 6e1ftdtwr80ty9zfkqzj,
    client_secret: 9vpczvqmkndob6doqjqa
    refresh_token: REFRESH_TOKEN
```

#### Create new Post
`POST /api/post`
```
Params:
    title, text
```

#### Update Post
`PUT /api/post/{id}`
```
Params:
    title, text
```

#### Delete Post
`DELETE /api/post/{id}`

#### View Post
`GET /api/post/{id}`

#### View all posts
`GET /api/post`


#### Create new User
`POST /cadastro`
```
Params:
    email, password
```

#### Update User
`PUT /api/user/{id}`
```
Params:
    email, password, api_key, api_secret
```

#### Suspend / Authorize User
`PUT /api/user/suspend/{id}`

#### Delete User
`DELETE /api/user/{id}`

#### View User
`GET /api/user/{id}`

#### View all Users
`GET /api/users`

#### View all Users and deleted
`GET /api/users/all`

