# Eleven Soft Backend Refactoring Test

# Implementação de Testes Unitários para a API com projeto base Laravel no endpoint de usuários

A implementação de métodos de teste unitário para o controlador de usuários (`UserController`) em uma aplicação Laravel. Os testes cobrem tanto cenários de sucesso quanto de falha, verificando os status codes apropriados para cada situação. (`UserControllerTest`)

1. Instalae e configura o projeto
   
`make install`

2. Inicializa os containers
   
`make up`

3. Renomeia o arquivo .env.example para .env
   
`make api-env`

4. Gera a chave do Laravel e adiciona no arquivo .env
   
`make api-key`

5. Executa os migrations e seeds
   
`make api-db`

6. Executa para criar o cliente OAuth de autenticação de usuários via Passport
   
`make api-passport-generate`

7. Executa para gerar a documentação Swagger
   
`make api-build-swagger`

8. Executa a execução dos testes unitário de Feature
   
`make api-test-feature`

