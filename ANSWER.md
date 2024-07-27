Eu me deparei com algumas dificuldades nesse desafio, a primeira delas foi o fato de nunca ter trabalhado com "swagger", agora entrou na minha pauta de estudos.
Outro ponto foi o fato de também nunca ter usado o passport, em projetos em que trabalhei sempre usamos o sanctum para lidar com autenticação.

Analisando o projeto, notei que ele estava na versão 10 do laravel, então fiz a atualização dele para a versão 11.x mais recente.

Também notei que os endpoits de 'users' estão sem as devidas validações, então eu as fiz utilizando o FormRequest do próprio laravel

Observei também que as rotas estão todas públicas sem autenticação, então usei o sanctum para trabalhar com autenticação, visto que nunca usei o passport, e não me senti tão seguro nesse momento.

Crie também rotas para autenticação, e registro de usuários. 