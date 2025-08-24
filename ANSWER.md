# Decisões de Refatoração
## Primeiros passos

### BackEnd
[x] Analisar o código existente para compreender estrutura e funcionalidades.
[x] Identificar entidades principais e suas responsabilidades.
[x] Separar responsabilidades.
[x] Criar Services para isolar a lógica de negócio, reduzindo a carga dos Controllers.
[x] Refatorar Controllers para delegar regras de negócio aos Services, mantendo-os focados em tratar requisições e respostas.
[x] Utilizar Request/FormData para validações e criação de mensagens interativas, retirando do Service a responsabilidade de validar informações.
[x] Utilização de Names nas Rotas para evitar conflitos em futuras mudanças.
[x] Adicionei uma camada de abstração (interface) para aplicar inversão de dependência, fazendo com que o controller dependa da interface em vez da implementação concreta do service.
[x] Necessario mudar os testes com mock na dependência com base na abstração da interface.

### FrontEnd
[x] Testar todas as funcionalidades implementadas.
[x] Criar interface de usuário para registro e manipulação de usuários no navegador.
[x] Exibir mensagens amigáveis na View, refletindo as respostas do sistema ao criar ou atualizar usuários.
[x] Desenvolver componentes interativos para gerenciar os métodos do UserService, como updateUser e deleteUser.
