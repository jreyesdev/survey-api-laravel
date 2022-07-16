<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @var array */
    public $user = [
        'name' => 'John Smith',
        'email' => 'jsmith@example.com',
        'password' => 'JohnSmith$1234',
        'password_confirmation' => 'JohnSmith$1234',
    ];

    /** @test */
    public function register_success()
    {
        $response = $this->postJson('api/register', $this->user);

        $response->assertExactJson([
            'user' => $response->baseResponse->original['user']->toArray(),
            'token' => $response->baseResponse->original['token']
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => $this->user['name'],
            'email' => $this->user['email'],
        ]);
    }

    /** @test */
    public function error_all_fields_are_required()
    {
        $response = $this->postJson('api/register', []);

        $response->assertJsonValidationErrors(['email', 'password', 'name'])->assertStatus(422);
    }

    /** @test */
    public function error_email_field_should_be_an_email()
    {
        $this->user['email'] = 'noisemail';

        $response = $this->postJson('api/register', $this->user);

        $response->assertJsonValidationErrors(['email'])->assertStatus(422);
    }

    /** @test */
    public function error_email_field_has_already_exists()
    {
        $user = User::factory()->create();

        $this->user['email'] = $user->email;

        $response = $this->postJson('api/register', $this->user);

        $response->assertJsonValidationErrors(['email'])->assertStatus(422);
    }

    /** @test */
    public function error_password_confirmation_not_match()
    {
        $this->user['password_confirmation'] = 'notmatch';

        $response = $this->postJson('api/register', $this->user);

        $response->assertJsonValidationErrors(['password'])->assertStatus(422);
    }

    /** @test */
    public function error_password_field_must_be_at_min_length()
    {
        $this->user['password'] = 'not';
        $this->user['password_confirmation'] = $this->user['password'];

        $response = $this->postJson('api/register', $this->user);

        $response->assertJsonValidationErrors(['password'])->assertStatus(422);
    }

    /** @test */
    public function error_password_field_must_contain_at_least_a_symbol()
    {
        $this->user['password'] = 'notasdf1234A';
        $this->user['password_confirmation'] = $this->user['password'];

        $response = $this->postJson('api/register', $this->user);

        $response->assertJsonValidationErrors(['password'])->assertStatus(422);
    }

    /** @test */
    public function error_password_field_must_contain_at_least_a_uppercase_letter()
    {
        $this->user['password'] = 'notasdf1234$';
        $this->user['password_confirmation'] = $this->user['password'];

        $response = $this->postJson('api/register', $this->user);

        $response->assertJsonValidationErrors(['password'])->assertStatus(422);
    }

    /** @test */
    public function error_password_field_must_contain_at_least_a_number()
    {
        $this->user['password'] = 'Nnotasdf$';
        $this->user['password_confirmation'] = $this->user['password'];

        $response = $this->postJson('api/register', $this->user);

        $response->assertJsonValidationErrors(['password'])->assertStatus(422);
    }
}
