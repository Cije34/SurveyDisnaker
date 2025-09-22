<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pertanyaan' => $this->faker->sentence(6, true),
            'kegiatan_id' => \App\Models\Kegiatan::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
