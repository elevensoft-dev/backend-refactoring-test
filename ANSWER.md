# ANSWER.md

## Decisões de Refatoração e Justificativas

### 1. Adoção de Clean Architecture

- **Separação Responsabilidades:** Toda a lógica de negócio foi movida para UseCases. Controllers agora apenas orquestram requests, DTOs e
  respostas, sem conter regras de negócio.
- **Uso de DTOs:** Dados trafegam entre camadas exclusivamente via DTOs, instanciados explicitamente com named params, garantindo tipagem,
  clareza e facilidade de manutenção.
- **Validação Centralizada:** Toda validação foi movida para FormRequests, eliminando validação inline e centralizando regras de entrada.
- **Respostas Padronizadas:** Controllers retornam sempre Resources, responses HTTP padronizados ou response()->noContent(), conforme o
  contexto.

### 2. Query Builders Dedicados

- **Implementação de QueryBuilders:** O Model `User` agora utiliza um QueryBuilder customizado (`UserQueryBuilder`), permitindo consultas
  mais expressivas e encapsulando lógica de queries complexas. Isso pode ser conferido em `app/Models/User.php` e
  `app/QueryBuilders/UserQueryBuilder.php`.

### 3. Docker e Ambiente de Desenvolvimento

- O ambiente anterior já separava serviços como app e mysql, mas era baseado no Laravel Sail, menos flexível para ajustes.
- O novo utiliza imagens Alpine para php-fpm/nginx, reduzindo tamanho e melhorando performance.
- Entrypoints agora são montados como volume, facilitando alterações rápidas no desenvolvimento local.
- Configurações de recursos e volumes estão mais explícitas e ajustadas para o fluxo do projeto.

### 4. Pacotes Adicionais Instalados

- **Clockwork:** Ferramenta instalada para profiling e debug de requests durante o desenvolvimento.
- **Scramble:** Adicionada para geração automática de documentação OpenAPI/Swagger.
- **Outros Pacotes:** Mantidos ou atualizados pacotes essenciais para testes (Pest, Mockery), análise estática (Larastan), autenticação (
  Sanctum), monitoramento de erros (Sentry), e code fixer/formatter (pint).

### 5. Testes

- **Testes Automatizados:** Foram adicionados testes de Feature.
