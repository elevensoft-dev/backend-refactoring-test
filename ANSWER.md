# Refatoração da API

## Explicação das mudanças realizadas

### Controllers mais "enxutos"

- Verifiquei que os Controllers continham todas as consultas e que chamavam diretamente as Models. Por esse motivo resolvi remover tudo de lá e criar a camada de Repository e deixar a responsabilidade de consultas no banco somente nela;

- Aém disso, quis criar os Controllers como "invokables" para deixá-lo bem enxuto, separando a responsabilidade de cada endpoint;

### Documentação

- Fiz a atualização do Swagger com as rotas que eu refatorei;

- A separação dos Controllers ajudou bastante a reduzir as annotations do Swagger em cada classse. Pude deixar a documentação somente do endpoint no arquivo;

### Validação dso dados enviados

- Criei FormRequest para validar a criação e alteração do usuário e garantir que não sejam enviados dados inválidos;

### Autenticação com Passport

- Notei que o Passport estava instalado mas não estava configurado. Usei para proteger as rotas de consulta ao banco. Criei enspoints para login e logout;

### Exceptions customizadas

- A decisão de criar Exceptions separadas foi para não utilizar blocos try...catch nos Controllers, deixando-os mais enxutos.

### API Resources

- Implementei API Resources para customizar os dados que serão enviados, evitandoo por exemplo de exibir todos os campos da Model para a resposta, algo bem parecido com o que é feito com "DTOs";

### Camada de Repository

- Concentrei todas as consultas nesta camada para deixar o código mais limpo e separar as responsabilidades;

### Camada de Service

- Criei essa camada para ser responsável por fazer as validações de regras de negócio de cada endpoint;

### Versionamento da API

- Além de separar os Controllers por cada ação, também resolvi criar um versionamento para os endpoints, podendo assim não perder a compatibilidade como futuras atualizações;

### Testes unitários e de feature

- Para garantir segurança do código criado, fiz a cobertura de praticamente todos os métodos e endpoints que crei, não só pelo "caminho feliz", mas pelo "caminho triste" também;
