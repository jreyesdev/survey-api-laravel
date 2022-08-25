<?php

namespace Tests\Feature\Survey;

use App\Models\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthUser;
use Tests\TestCase;

class ListSurveysTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_all_surveys_auth_user()
    {
        $user = AuthUser::createAuthUser();

        $surveys = Survey::factory(3)->create([
            'user_id' => $user[0]->id,
        ]);

        $surveys = $surveys->map(function ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'slug' => $item['slug'],
                'status' => $item['status'] !== 'draft',
                'description' => $item['description'],
                'expire_date' => $item['expire_date'],
                'questions' => [],
            ];
        });

        $response = $this->getJson('api/surveys', [
            'Authorization' => 'Bearer ' . $user[1]
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => $surveys->toArray()
        ]);
    }
}
