<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_endpoints_require_authentication(): void
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);

        $response = $this->getJson('/api/users/1');
        $response->assertStatus(401);

        $response = $this->postJson('/api/users', []);
        $response->assertStatus(401);

        $response = $this->putJson('/api/users/1', []);
        $response->assertStatus(401);

        $response = $this->deleteJson('/api/users/1');
        $response->assertStatus(401);
    }

    public function test_oauth_endpoint_exists(): void
    {
        $response = $this->get('/oauth/token');
        $response->assertStatus(405);
    }
}
