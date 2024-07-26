<?php

namespace Tests\Unit;

use App\Http\Requests\Auth\AuthFormRequest;
use Tests\TestCase;

class AuthFormRequestTest extends TestCase
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

        $this->rules     = (new AuthFormRequest())->rules();
        $this->validator = $this->app['validator'];
    }

     public function testForAuthIsValidEmail(): void
     {

        $this->assertTrue($this->validateField('email', 'usern@elevensoft.dev'));
        $this->assertTrue($this->validateField('email', 'usern@elevensoft'));
        $this->assertFalse($this->validateField('email', 'userelevensoft.com'));
        $this->assertFalse($this->validateField('email', ''));

     }

     public function testForAuthIsValidPassword(): void
     {

        $this->assertTrue($this->validateField('password', 'inf41234'));
        $this->assertFalse($this->validateField('password', 'inf123'));
        $this->assertFalse($this->validateField('email', ''));

     }

    protected function validateField(string $field, $value): bool
    {
        return $this->validator->make(
            [$field => $value],
            [$field => $this->rules[$field]]
        )->passes();
    }
}
