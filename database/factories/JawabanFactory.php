<?php

namespace Database\Factories;

use App\Models\Peserta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jawaban>
 */
class JawabanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $survey = \App\Models\Survey::query()->inRandomOrder()->first();

        if (! $survey) {
            $survey = \App\Models\Survey::factory()->create();
        }

        $surveyId = $survey->id;
        $surveyType = $survey->type;

        return [
            'peserta_id' => Peserta::query()->inRandomOrder()->value('id') ?? Peserta::factory(),
            'survey_id' => $surveyId,
            'jawaban' => $surveyType === 'choice'
                ? $this->faker->randomElement(['Sangat baik', 'Baik', 'Cukup baik', 'Buruk'])
                : $this->faker->text(),
            'tipe' => $surveyType,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
