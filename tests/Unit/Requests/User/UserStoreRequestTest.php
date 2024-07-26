<?php

namespace Tests\Unit;

use App\Http\Requests\User\UserStoreRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Tests\TestCase;

class UserStoreRequestTest extends TestCase
{
    private $rules;
    private $validator;
        /**
     * Set up operations
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->rules = (new UserStoreRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    public function testForUserIsValidName(): void
     {

        $this->assertTrue($this->validateField('name', fake()->name()));
        $this->assertFalse($this->validateField('name', ''));

     }

     public function testForUserIsValidEmail(): void
     {

        $this->assertTrue($this->validateField('email', fake()->email()));
        $this->assertTrue($this->validateField('email', 'user@elevensoft'));
        $this->assertFalse($this->validateField('email', 'userelevensoft.com'));
        $this->assertFalse($this->validateField('email', 'example@elevensoft.dev'));
        $this->assertFalse($this->validateField('email', ''));

     }

     public function testForUserIsValidPassword(): void
     {

        $this->assertTrue($this->validateField('password', Str::random()));
        $this->assertFalse($this->validateField('password', Str::random(7)));
        $this->assertFalse($this->validateField('password', ''));

     }

    protected function validateField(string $field, $value): bool
    {
        return $this->validator->make(
            [$field => $value],
            [$field => $this->rules[$field]]
        )->passes();
    }
}
