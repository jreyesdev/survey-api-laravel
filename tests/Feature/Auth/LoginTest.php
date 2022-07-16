<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @var User|Null */
    public $user = null;

    private function createUser()
    {
        $this->user = User::factory()->create([
            'password' => 'password'
        ]);
    }

    /** @test */
    public function can_login_success()
    {
        $this->withoutExceptionHandling();

        $this->createUser();

        $response = $this->postJson('api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response->assertExactJson([
            'user' => $this->user->toArray(),
            'token' => $response->baseResponse->original['token']
        ])->assertStatus(200);
    }

    /** @test */
    public function error_all_fields_are_required()
    {
        $response = $this->postJson('api/login', []);

        $response->assertJsonValidationErrors(['email', 'password'])->assertStatus(422);
    }

    /** @test */
    public function error_email_not_exists()
    {
        $this->createUser();

        $response = $this->postJson('api/login', [
            'email' => 'prueba@prueba.com',
            'password' => '12345678'
        ]);

        $response->assertJsonValidationErrors(['email'])->assertStatus(422);
    }

    /** @test */
    public function error_credentials()
    {
        $this->createUser();

        $response = $this->postJson('api/login', [
            'email' => $this->user->email,
            'password' => '12324'
        ]);

        $response->assertJsonValidationErrors(['email'])->assertStatus(422);
    }
}
