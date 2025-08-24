# Arquitetura & Decisões Técnicas

# Resumo rápido

* Arquitetura: **Hexagonal (Ports & Adapters)**
* Injeção: **Dependência via interfaces** (DI)
* Autenticação: **Laravel Passport (OAuth2)**
* Camadas: Controllers → Requests → Services → Repositories → Models → Resources
* Testes: Unitários via mocks nas portas; Integration com SQLite in-memory quando necessário

---

# Por que Hexagonal (Ports & Adapters)

* **Separação de responsabilidades.** Domínio (services) não sabe da infra (Eloquent, Passport). Só conhece interfaces.
* **Testabilidade.** Substitui implementações por mocks facilmente.
* **Troca de infra sem dor.** Troca Eloquent por outro ORM, ou Passport por outro serviço, trocando adapters apenas.
* **Disciplina.** Lógica de negócio fica no lugar certo.

**Vantagem prática:** mudanças em infra provocam poucas linhas a alterar — geralmente um adapter (repository) novo.

---

# Injeção de Dependências (DI)

* Em vez de um único ponto onde são registrados todos os bindings, o projeto usa **módulos de DI por domínio/entidade** (ex.: `UserDi`, `AuthDi`) que declaram os bindings de services e repositories dessa área.
* Há uma classe base `DependencyInjection` que recebe a `Application` no construtor e expõe `configure()` para registrar os bindings listados em duas arrays: `repositoriesConfigurations()` e `servicesConfiguration()`.
* Todas as classes `*Di` são agrupadas por `DependencyInjection::providers($app)` (um factory que retorna uma `Collection`) e a `AppServiceProvider::register()` simplesmente itera essa lista e chama `configure()` em cada módulo.

**Por que isso é bom:**

* Organização: cada domínio é responsável por seus próprios bindings — mais fácil de navegar e manter.
* Escalabilidade: adicionar um novo módulo (ex.: `BillingDi`) não polui um provider gigante.
* Testabilidade: fica simples trocar bindings por mocks no bootstrap dos testes (ou usando `$this->app->instance`).

**Exemplo (padrão usado no projeto):**

```php
// App/Providers/DependencyInjection/UserDi.php
protected function servicesConfiguration(): array
{
    return [
        [IUserListingService::class, UserListingService::class],
        // ... outros services
    ];
}

protected function repositoriesConfigurations(): array
{
    return [
        [IUserRepository::class, UserRepository::class]
    ];
}
```

```php
// App/Providers/DependencyInjection/DependencyInjection.php
public function configure()
{
    $configurations = array_merge(
        $this->repositoriesConfigurations(),
        $this->servicesConfiguration()
    );
    foreach ($configurations as $configuration) {
        $this->app->bind($configuration[0], $configuration[1]);
    }
}
```

```php
// App/Providers/AppServiceProvider.php
public function register(): void
{
    DependencyInjection::providers($this->app)->each(function (DependencyInjection $di): void {
        $di->configure();
    });
}
```

---

# Por que usar Laravel Passport (OAuth2)

* Implementa OAuth2 (tokens, refresh, clients, scopes) — pronto para APIs públicas e integrações.
* Integra ao Laravel (`$user->createToken()`, middleware `auth:api`).
* Permite revogação de tokens (logout) e políticas mais finas com scopes.

**Trade-offs:** precisa de tabelas adicionais e setup (keys/clients) — compensa se você precisa de um esquema de tokens robusto.

---

# Papel de cada camada (como tudo funciona)

## Controllers

* Recebem `FormRequest` validados.
* Orquestram chamadas aos services.
* Retornam `JsonResponse` ou `JsonResource`.
* Contêm as anotações Swagger (contrato público).

## FormRequest

* Validação centralizada e mensagens customizadas.
* Evita lógica de validação espalhada.

## Services (Domain)

* Contêm orquestração e regras de negócio.
* Recebem `IUserRepository` (interface) via DI.
* Lançam exceções de domínio (`UserNotFoundException`, `InvalidUserDataException`).
* Não fazem queries diretas — delegam ao repository.

## Repositories (Adapters)

* Implementam as interfaces do core e encapsulam Eloquent/queries.
* Ex.: `paginateUsers`, `createUser`, `updateUser`, `deleteUser`.

## Models/Eloquent

* Entidade persistida. Usados pelos adapters.

## Resources (JsonResource)

* Centralizam shape do output (ex.: `createdAt` formatado).
* Garantem consistência na API.

## Exceptions & Handler

* Exceções de domínio lançadas nas services.
* `Handler` mapeia para respostas JSON com códigos corretos (401, 404, 400, 500).

---

# Fluxos exemplares (curtos)

## Login

1. `LoginAuthRequest` valida email/senha.
2. Controller chama `AuthRepository->login($request)`.
3. Repository usa `Auth::attempt()` e `$user->createToken()` (Passport).
4. Retorna array com `access_token`, `token_type`, `expires_in`, `user`.

## Update User

1. Controller valida via `UserUpdateRequest`.
2. Service valida payload (`empty()`), busca user via repo.
3. Service monta `User` com campos alterados; faz bcrypt na senha.
4. Repo faz `update`. Service retorna `UserResource` do user atualizado.

---

# Tratamento de erros padrão

* 400 — dados inválidos / `InvalidUserDataException`
* 401 — `AuthenticationException`
* 404 — `UserNotFoundException` / `ModelNotFoundException`
* 500 — fallback (mostrar detalhe somente em `app.debug`)

---

# Swagger / API Docs (l5-swagger)

* Anote controllers e rotas. O swagger-php precisa das anotações para cada path. Sim, é trabalho manual por rota.
* Defina `components/schemas` (ex.: `UserResource`) e use `@OA\JsonContent(ref="#/components/schemas/UserResource")` nas respostas.
* Defina `securitySchemes` para `bearerAuth` (OAuth2/Password flow):

```php
/**
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 */
```

* Marque endpoints privados:

```php
 * @OA\Security({{"bearerAuth":{}}})
```

---

# Testes — estratégia

* **Unit** (services): mocka somente as `interfaces` do domínio (`IUserRepository`) com Mockery.
* **Unit** (repositories): prefiri testes de integração leves com SQLite-in-memory; pois mocks de Eloquent (`alias:`) causam fragilidade.
* **Feature**: usei `DatabaseMigrations` + criação de client Passport no `setUp()` (trait `CreatesPassportClients`).

---

# Boas práticas

* Hash de senha no service (não no controller nem no repo) — regra de negócio.
* Repositories retornam `Model`/`Paginator` — serviço transforma em `Resource`.
* Exceptions de domínio para fluxo de erros (Handler converte em JSON).
* Traits para testes (ex.: `CreatesPassportClients`) para evitar repetição.

---

# Checklist rápido para devs novos

* Validação → `FormRequest`
* Lógica → `Service`
* Persistência → `Repository`
* Serialização → `Resource`
* Documentar rota com `@OA` na controller
* Registrar `I*` → `*Repository` no ServiceProvider
* Testar services com mocks, repositories com SQLite

---
