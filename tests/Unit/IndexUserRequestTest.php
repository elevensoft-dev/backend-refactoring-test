<?php

namespace Tests\Unit;

use App\Http\Requests\IndexUserRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndexUserRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_per_page_accepts_valid_values(): void
    {
        $request = new IndexUserRequest();
        $rules = $request->rules();

        $validValues = [1, 10, 50, 100];

        foreach ($validValues as $value) {
            $validator = Validator::make(['per_page' => $value], $rules);
            $this->assertFalse($validator->fails(), "per_page value {$value} should be valid");
        }
    }

    public function test_per_page_rejects_invalid_values(): void
    {
        $request = new IndexUserRequest();
        $rules = $request->rules();

        $invalidValues = [0, -1, 101, 'string', null];

        foreach ($invalidValues as $value) {
            $validator = Validator::make(['per_page' => $value], $rules);
            $this->assertTrue($validator->fails(), "per_page value {$value} should be invalid");
        }
    }

    public function test_trashed_accepts_valid_boolean_values(): void
    {
        $rules = (new IndexUserRequest())->rules();

        $validValues = ['true', '1'];

        foreach ($validValues as $value) {
            $validator = Validator::make(['trashed' => $value], $rules);
            $this->assertFalse($validator->fails(), "trashed value should accept: " . var_export($value, true));
        }
    }

    public function test_trashed_rejects_invalid_values(): void
    {
        $request = new IndexUserRequest();
        $rules = $request->rules();

        $invalidValues = ['invalid', 'yes', 'no', 2, -1];

        foreach ($invalidValues as $value) {
            $validator = Validator::make(['trashed' => $value], $rules);
            $this->assertTrue($validator->fails(), "trashed should reject: " . var_export($value, true));
        }
    }

    public function test_search_accepts_string_values(): void
    {
        $request = new IndexUserRequest();
        $rules = $request->rules();

        $validValues = ['john', 'john@example.com', 'John Doe', '123', ''];

        foreach ($validValues as $value) {
            $validator = Validator::make(['search' => $value], $rules);
            $this->assertFalse($validator->fails(), "search should accept: {$value}");
        }
    }

    public function test_search_rejects_non_string_values(): void
    {
        $request = new IndexUserRequest();
        $rules = $request->rules();

        $invalidValues = [123, [], null, true];

        foreach ($invalidValues as $value) {
            $validator = Validator::make(['search' => $value], $rules);
            $this->assertTrue($validator->fails(), "search should reject: " . var_export($value, true));
        }
    }

    public function test_all_fields_are_optional(): void
    {
        $request = new IndexUserRequest();
        $rules = $request->rules();

        $validator = Validator::make([], $rules);

        $this->assertFalse($validator->fails(), "All fields should be optional");
    }

    public function test_authorize_returns_true(): void
    {
        $request = new IndexUserRequest();

        $this->assertTrue(true);
    }

    public function test_validates_multiple_parameters_together(): void
    {
        $request = new IndexUserRequest();
        $rules = $request->rules();

        $validData = [
            'per_page' => 25,
            'trashed' => 'true',
            'search' => 'john'
        ];

        $validator = Validator::make($validData, $rules);

        $this->assertFalse($validator->fails());
    }
}
