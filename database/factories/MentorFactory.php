<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mentor>
 */
class MentorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'no_hp' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'alamat' => $this->faker->address(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'materi' => $this->faker->sentence(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
