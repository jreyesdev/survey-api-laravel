<?php

namespace Tests\Feature\Survey;

use App\Models\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\AuthUser;
use Tests\TestCase;

class ListSurveysTest extends TestCase
{
    use RefreshDatabase;

    /** Crea encuestas al usuario segun id
     * @param int
     * @param int
     * @return Collection
     */
    private function createSurveys(int $id, int $count = 3): Collection
    {
        return Survey::factory($count)->create([
            'user_id' => $id,
        ]);
    }

    /** @test */
    public function list_all_surveys_auth_user()
    {
        [$user, $token] = AuthUser::createAuthUser();

        $surveys = $this->createSurveys($user->id);

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
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => $surveys->toArray()
        ]);
    }

    /** @test */
    public function user_only_sees_their_survery()
    {
        [$user, $token] = AuthUser::createAuthUser();
        [$user2] = AuthUser::createAuthUser();

        $surveys = $this->createSurveys($user->id);
        $this->createSurveys($user2->id, 5);

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

        $response = $this->getJson('api/surveys/' . $surveys[0]['id'], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)->assertJson($surveys[0]);
    }

    /** @test */
    public function user_should_not_see_a_survey_of_other_user()
    {
        [$user, $token] = AuthUser::createAuthUser();
        [$user2] = AuthUser::createAuthUser();

        $this->createSurveys($user->id);
        $surveys = $this->createSurveys($user2->id, 5);

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

        $response = $this->getJson('api/surveys/' . $surveys[0]['id'], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_should_not_see_a_survey_that_does_not_exist()
    {
        [$user, $token] = AuthUser::createAuthUser();

        $this->createSurveys($user->id);

        $response = $this->getJson('api/surveys/10', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(404);
    }
}
