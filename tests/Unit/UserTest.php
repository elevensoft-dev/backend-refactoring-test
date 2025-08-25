<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_uses_soft_deletes_trait(): void
    {
        $user = new User();
        $traits = class_uses_recursive($user);

        $this->assertContains(SoftDeletes::class, $traits);
    }

    public function test_fillable_attributes_are_correct(): void
    {
        $user = new User();
        $expected = ['name', 'email', 'password'];

        $this->assertEquals($expected, $user->getFillable());
    }

    public function test_hidden_attributes_include_password(): void
    {
        $user = new User();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    public function test_casts_email_verified_at_to_datetime(): void
    {
        $user = new User();
        $casts = $user->getCasts();

        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
    }

    public function test_password_is_hashed_when_set(): void
    {
        $user = User::factory()->create(['password' => 'plaintext']);

        $this->assertNotEquals('plaintext', $user->password);
        $this->assertTrue(Hash::check('plaintext', $user->password));
    }

    public function test_factory_creates_valid_user(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->password);
        $this->assertNull($user->deleted_at);
    }

}
