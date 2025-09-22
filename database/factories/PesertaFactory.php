<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peserta>
 */
class PesertaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'alamat' => $this->faker->address(),
            'NIK' => $this->faker->unique()->numerify('##########'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'pendidikan_terakhir' => $this->faker->randomElement(['SD', 'SMP', 'SMA']),
            'no_hp' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
