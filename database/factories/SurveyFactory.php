<?php

namespace Database\Factories;

use App\Models\Kegiatan;
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
            'type' => 'choice',
            'kegiatan_id' => Kegiatan::query()->inRandomOrder()->value('id') ?? Kegiatan::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
