# Ingresse Backend Devloper Test

[![Build Status](https://travis-ci.com/BlackBarba/ingresse-dev-test.svg?branch=master)](https://travis-ci.com/BlackBarba/ingresse-dev-test)
[![codecov](https://codecov.io/gh/BlackBarba/ingresse-dev-test/branch/master/graph/badge.svg)](https://codecov.io/gh/BlackBarba/ingresse-dev-test)

## Sobre
Uma API REST para gerenciamento de informações de usuário.
A API funciona com um servidor NGINX, e foi construída em PHP com o framework Laravel, utilizando o banco de dados MySQL, e para cache foi utilizado Redis. A API também possui testes automatizados utilizando o PHPUnit, com relatórios de cobertura e integração contínua utilizando Travis CI.

## Instalação
### Programas necessários
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/) (Opicional)
### Primeiros passos
- Faça o download do zip ou clone este repositório com `git clone https://github.com/BlackBarba/ingresse-dev-test`
- Tenha certeza que o Docker esteja rodando
- Entre na pasta do repositório
- Execute o comando `sudo chmod 777 src/wait-for-it.sh`
- Execute o arquivo build.sh, `bash build.sh`
- Espere até que todos os containers sejam montados, e que todas as dependências sejam baixadas e instaladas
- Feito isso a aplicação ficará disponível em http://localhost:80 e o PHPMyAdmin estará disponível em http://localhost:8080
## Requisições
### Listar usuários
Retorna um JSON com uma lista de usuários, esta lista é paginada e possui um cache de um minuto
#### Url
/users
#### Método
`GET`
#### Query string
- `page` Integer (Opcional) - A página atual
- `perPage` Integer (Opcional) - A quantidade de usuários a serem mostrados por página


### Mostrar um usuário
Retorna um JSON com informações sobre um usuário, esta consulta possui um cache de um minuto
#### Url
/users/:id
#### Método
`GET`
#### Parâmetros na url
- `id` Integer (Obrigatório) - O ID do usuário que se quer obter as informações


### Cadastrar um usuário
Cadastra um usuário no banco de dados, além de criar um cache de um minuto para esse usuário recém cadastrado, também remove o cache da listagem.
#### Url
/users
#### Método
`POST`
#### Body

O body pode ser enviado como Multipart, URL encoded ou JSON

- `name` string, máximo de 255 caracteres (Obrigatório) - O nome do usuário
- `username` string, máximo de 255 caracteres, não pode estar sendo utilizado por outro usuário, alfanumérico, sem espaços, caracteres especiais permitidos: ".-_" (Obrigatório) - O apelido do usuário
- `email` string, máximo de 255 caracteres, não pode estar sendo utilizado por outro usuário, email válido (Obrigatório) - O email do usuário
- `password` string, máximo de 255 caracteres (Obrigatório) - A senha do usuário
- `birthday` date (Opicional) - A data de aniversário do usuário


### Atualizar um usuário
Atualiza um usuário no banco de dados, além de criar um cache de um minuto para esse usuário recém atualizado, também remove o cache da listagem.
#### Url
/users/:id
#### Método
`PUT`
#### Parâmetros na url
- `id` Integer (Obrigatório) - O ID do usuário que se quer atualizar as informações
#### Body

O body pode ser enviado como URL encoded ou JSON

- `name` string, máximo de 255 caracteres (Obrigatório) - O nome do usuário
- `username` string, máximo de 255 caracteres, não pode estar sendo utilizado por outro usuário, alfanumérico, sem espaços, caracteres especiais permitidos: ".-_" (Obrigatório) - O apelido do usuário
- `email` string, máximo de 255 caracteres, não pode estar sendo utilizado por outro usuário, email válido (Obrigatório) - O email do usuário
- `password` string, máximo de 255 caracteres (Opicional) - A senha do usuário
- `birthday` date (Opicional) - A data de aniversário do usuário

### Remover um usuário
Remove um usuário do banco de dados, além de remover o cache deste usuário, também remove o cache da listagem.
#### Url
/users/:id
#### Método
`DELETE`
#### Parâmetros na url
- `id` Integer (Obrigatório) - O ID do usuário que se quer remover

## Testes

Para realizar os testes execute o comando `docker exec -it dev-test-application vendor/bin/phpunit`

Os relatórios de cobertura serão gerados em:  `src/report/`, e também há os relatórios gerados pela ultima build feita no Travis CI, disponíveis através da Codecov pelo link: https://codecov.io/gh/BlackBarba/ingresse-dev-test