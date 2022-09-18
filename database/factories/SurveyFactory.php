<?php

namespace Database\Factories;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Survey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'status' => rand(0, 1) ? true : false,
            'description' => $this->faker->paragraph(5),
            'expire_date' => now()->addMonths(rand(1, 5))->format('Y-m-d h:i:s')
        ];
    }
}
