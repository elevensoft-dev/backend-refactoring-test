<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('verificando se existe a rota de users e somente usuário logado pode acessar', function () {

    $response = Sanctum::actingAs(User::factory()->create());
    $this->get('/api/users')
        ->assertOk();
});
