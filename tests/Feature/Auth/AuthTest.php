<?php

namespace Tests\Feature;

use Tests\TestCase;
class AuthTest extends TestCase
{

     public function testCanBeMakeLogin(): void
     {

        $userData = [
            'email' => 'example@elevensoft.dev',
            'password' => 'password'
        ];

         $response = $this->postJson('/api/v1/auth/login', $userData);

         $response->assertOK();
         $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'

            ],
            'token'
        ]);

     }

     public function testCannotMakeloginWithInvalidCredentials(): void
     {

        $userData = [
            'email' => 'admin@teste.com',
            'password' => 'admin@teste'
        ];

         $response = $this->postJson('/api/v1/auth/login', $userData);

         $response
            ->assertUnauthorized();

     }

     public function testCanMakeLogout(): void
     {
        $headers = $this->makeAuth();
        $response = $this->postJson('/api/v1/auth/logout', [], $headers);
        $response->assertOK();
     }
}
