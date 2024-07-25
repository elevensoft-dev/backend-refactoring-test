# DECISOES QUE TOMEI NO PROCESSO DE REFATORAÇÃO

- API de usuários usei o padrão Repository com contrato, junto com um serviço para a lógica de negócio e controladores limpos, resulta em um código mais organizado, fácil de manter.

- A separação das responsabilidades torna o código mais modular e fácil de manter. Alterações em uma camada (por exemplo, a lógica de negócio) não afetam diretamente outras camadas (por exemplo, o controlador).

## Separação de Responsabilidades

- InterfaceRepository: Define os métodos essenciais (all, find, create, update, delete) que qualquer repositório deve implementar.

- AbstractRepository: Implementa a interface InterfaceRepository e fornece a lógica básica para manipulação de modelos.

- UserRepository: Extende AbstractRepository e especifica que o modelo é User.

## UserService Service para Lógica de Negócio:

- UserService: Contém a lógica de negócio para manipulação dos usuários (busca, criação, atualização e deleção). O serviço interage com o repositório para realizar essas operações e formata a resposta.

## UserController Limpo

- UserController: Recebe as requisições, delega a lógica de negócio para o UserService e retorna as respostas formatadas.

## ValidateUserRequest Validação Centralizada

- ValidateUserRequest: Classe responsável pela validação dos dados de entrada para criação e atualização de usuários. Contém as regras e mensagens de validação.

## UserControllerTest Testes Automatizados

- Testes para garantir que os métodos do UserController funcionem conforme esperado. Isso inclui testar as operações de indexação, exibição, criação, atualização e deleção de usuários.

## Legibilidade e Clareza:

- A estrutura modular facilita a adição de novas funcionalidades ou a modificação das existentes sem causar grandes impactos na aplicação como um todo.

## Mudança no makefile

- A estrutura que estava como padrão não funcionava na minha maquina então adicionei uma condicional `||` para que funcione em outras versões inclusive a minha ex: 
`docker-compose down || docker compose down`

## Mudança no Readme.md 

- a porta da aplicaçaõ estava em `http://localhost:8000`. Fiz a mudança para `http://localhost:8080`. O mesmo para a documentação `http://localhost:8000/api/documentation`. Fiz a mudança para `http://localhost:8080/api/documentation`.


## Meio de Contato 

- whats: (98) 984242805
- email: newtonplay007@gmail.com

` Espero seu retorno ...`
