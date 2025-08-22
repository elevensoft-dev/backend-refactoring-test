# Mudanças e Decisões

### 1. Implementação do uso de autorização nas rotas da API.
- Funcionalidade já era suportada, porém não foi configurada corretamente.

### 2. Correção do fluxo OAuth2 na documentação Swagger.
 - Garantir que a documentação reflita a autenticação configurada no projeto.
 - Permitir o teste pela interface do Swagger UI.

### 3. Padronização do retorno da API users com um resource (UserResource).
 - Padronizar o retorno e evitar a exibição de campos inseguros/desnecessários.

### 4. Uso de paginação e filtros na listagem de todos os usuários (api/users/).
 - Melhorar performance quando houver um grande número de registros.
 - Facilitar buscas.

### 5. Refatoração e validações da API users.
 - Correções de segurança.
 - Uso de FormRequests para os métodos store e update.
 - Validação de parametros das rotas.

### 6. Criação de testes para a API users.
 - Boas práticas de desenvolvimento.
 - Assegurar que as funcionalidades agem como o esperado.