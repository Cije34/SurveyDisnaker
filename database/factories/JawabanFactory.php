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
        return [
            'peserta_id' => Peserta::inRandomOrder()->first()->id ?? Peserta::factory(),
            'survey_id' => \App\Models\Survey::query()->inRandomOrder('id')->first()->id,
            'jawaban' => $this->faker->text(),
            'tipe' => $this->faker->randomElement(['essay', 'survey']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
