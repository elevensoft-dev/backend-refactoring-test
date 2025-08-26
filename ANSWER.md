# Processo de Refatoração da API

## Mudanças Implementadas:

### 1. Melhorias makefile
- Ao executar os comandos do docker gerou um erro relacionado a versão mais recente do docker, então tive que alterar para funcionar.

### 2. Tratamento de erros e validação
- O que indentifiquei no primeiro teste da api, foi que não estava validando os campos enviados, e isso gerava alguns erros. Adicionei blocos de tratamento de excessão aos métodos e também a validação dos campos enviados.

### 3. Implementação da autenticação
- Também observei que as rotas não tinham autenticação, tornando a aplicação pouco segura. Optei por utilizar o Sanctum, pois gera um sistema simples e seguro de autenticação por token. Adicionei duas novas rotas, 'login' e 'logout', e adicionei o middleware do Sanctum às rotas de usuário.

### 4. Atualização do Swagger
- Adicionei as novas rotas de autenticação a documentação do Swagger.

### 5. Implementação de testes automatizados
- Adicionei os testes automatizados para todas as rotas da aplicação.

### 5. Implementação do SOLID e Repository Pattern
- Senti que ainda poderia melhorar a qualidade do código, então revolvi aplicar alguns conceitos de SOLID para deixar a arquitetura mais limpa, criei services para separar as regras dos controladores, adicionei a validação em arquivos separados, criei Repositories para gerenciar os dados, junto com a criação de interfaces para abstrair as dependencias e garantir que os métodos existam quando forem chamados.

### Benefícios Alcançados
- Código mais limpo, modular e organizado, seguindo princípios SOLID.
- Validação centralizada e tratamento de erros consistente.
- Segurança aprimorada com autenticação via Sanctum.
- Facilidade para manutenção e evolução futura.
- Testes automatizados garantindo maior confiabilidade.
- Documentação atualizada e alinhada com a API real.
- Separação clara de responsabilidades entre controllers, services e repositórios.
