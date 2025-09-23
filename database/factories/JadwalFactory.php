<?php

namespace Database\Factories;

use App\Models\Mentor;
use App\Models\Penjab;
use App\Models\Tempat;
use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'penjab_id' => Penjab::factory(),
            'kegiatan_id' => Kegiatan::factory(),
            'tempat_id' => Tempat::factory(),
            'tanggal_mulai' => $this->faker->dateTimeBetween('now', '+1 week'),
            'tanggal_selesai' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'jam_mulai' => $this->faker->time(),
            'jam_selesai' => $this->faker->time(),
            'status' => $this->faker->boolean(),
        ];
    }
}
