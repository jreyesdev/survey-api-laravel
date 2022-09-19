<?php

namespace Tests\Feature\Survey;

use App\Models\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\AuthUser;
use Tests\TestCase;

class CreateSurveyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function user_can_create_survey_with_all_fields()
    {
        $token = AuthUser::createAuthUser()[1];
        $survey = Survey::factory()->make()->toArray();

        $response = $this->postJson('api/surveys', $survey, [
            'Authorization' => 'Bearer ' . $token
        ]);

        // dd($response->baseResponse->original);
        $surveyDb = Survey::first();

        $response->assertCreated()->assertJson([
            'title' => Str::lower($survey['title']),
            'status' => $survey['status'] !== 'draft',
            'description' => Str::lower($survey['description']),
            'expire_date' => $survey['expire_date'],
            'id' => $surveyDb['id'],
            'slug' => $surveyDb['slug'],
            'created_at' => $surveyDb['created_at']->toJSON(),
            'updated_at' => $surveyDb['updated_at']->toJSON(),
            'questions' => []
        ]);
    }

    /** @test */
    public function a_user_guest_cant_should_create_a_survey()
    {
        $survey = Survey::factory()->make()->toArray();

        $response = $this->postJson('api/surveys', $survey);

        $response->assertUnauthorized()->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    /** @test */
    public function error_fields_title_and_status_are_required()
    {
        $token = AuthUser::createAuthUser()[1];

        $response = $this->postJson('api/surveys', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['title', 'status']);
    }

    /** @test */
    public function error_field_status_should_be_type_boolean()
    {
        $token = AuthUser::createAuthUser()[1];

        $response = $this->postJson('api/surveys', [
            'title' => 'Titulo de la encuesta',
            'status' => 'asfas'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['status']);
    }

    /** @test */
    public function error_field_title_should_have_a_max_100_characters()
    {
        $token = AuthUser::createAuthUser()[1];

        $response = $this->postJson('api/surveys', [
            'title' => $this->faker->paragraph(4),
            'status' => true
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function error_field_expire_date_should_be_greater_than_tomorrow_date()
    {
        $token = AuthUser::createAuthUser()[1];

        $response = $this->postJson('api/surveys', [
            'title' => $this->faker->sentence(1),
            'status' => $this->faker->boolean(),
            'expire_date' => now()->addDays()->format('Y-m-d')
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['expire_date']);
    }
}
