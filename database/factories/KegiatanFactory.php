<?php

namespace Database\Factories;

use App\Models\TahunKegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kegiatan>
 */
class KegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kegiatan' => $this->faker->sentence(3),
            'tahun_kegiatan_id' => TahunKegiatan::query()->inRandomOrder()->value('id')
                ?? TahunKegiatan::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
