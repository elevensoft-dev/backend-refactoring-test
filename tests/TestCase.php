<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function makeAuth(): array
    {
        $userData = [
            'email' => 'example@elevensoft.dev',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/v1/auth/login', $userData);
        $data = json_decode($response->getContent());

        $headers = [
            'Authorization' => 'Bearer ' . $data->token
        ];

        return $headers;
    }


    public function makeUnauthorizedAuth(): array
    {
        $userData = [
            'email' => 'example@elevensoft.dev',
            'password' => 'teste'
        ];

        $response = $this->postJson('/api/v1/auth/login', $userData);
        $data = json_decode($response->getContent());

        $headers = [];

        return $headers;
    }
}
