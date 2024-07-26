# Eleven Soft Backend Refactoring Test Answers

    Adicionado o Dockerfile para subir o ambiente através de containers docker

    Adicionado o arquivo .env.testing para a base de dados de teste

    No Arquivo README.md nos itens Instalation and Setup e Documentation o endereço esta com a porta errada ao invés de 8000 é 8080
    
    Criado a pasta Api/v1 dentro de Controllers para criar tipo um versionamento das apis. 

    Utilização do Laravel Passport para garantir maior segurança nas operações de Crud, permitindo que somente usuários devidamente logados possam inserir, recuperar, atualizarinserir, atualizar, deleta

    Utiliização de RepositoryPattern para separar lógica de acesso a dados e a lógica da camada de negócio.

    Utilização de FormRequests no Crud para validar os dados enviados pela requisição antes de processa-los, dentre os beneficios estão separação de responsabilidades, centralização das regras de validações, melhor segurança

    Método Index retorna a listagem de todos os usuários cadastrados, por esse motivo foi adicionada paginação para evitar possível sobrecarregamento da API, podendo causar um timeout

    Pensando na gestão dos dados do usuário, e que o mesmo poderá apenas alterar a senha, no update de usuários, não será permitido alterar a senha, a alteração da senha será feita em uma api especifica para alteração de senha.

    Utilização de Resources para estruturar e formatar os dados da Model a serem retornados

    No Método delete, foi adicionado a trait de Softdeletes, e adicionado a coluna deleted_at na migration, para evitar a exclusão fisica do usuário e apenas inativá-lo, pois para exclusão do usuário, caso tenha dados em outra tabela associado a ele, estes dados também deverão ser excluídos, e podem ser dados sensíveis que não poderam ser excluídos.

    Adicionado Testes Unitários e de Integração para garantir que o código alterado não afetem o comportamento da aplicação

    Adicionado blocos try catch para tratamento de erros na controller 
    Adicionado Códigos HTTP de acordo com as respostas das solicitações, 
    201 para inserção, 200 para lista, edição e atulização e 500 para erros no bloco Catch
