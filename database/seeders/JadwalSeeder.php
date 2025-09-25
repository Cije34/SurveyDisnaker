<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jadwal::factory()
            ->count(1000)
            ->create();
        // You can add more logic here if needed, such as attaching mentors or activities
        // to the jadwals created, similar to what you did in JadwalMentorSeeder.
        // For example:
        // Jadwal::all()->each(function ($jadwal) {
        //     $jadwal->mentors()->attach(Mentor::factory()->count(2)->create());
        //     $jadwal->kegiatan()->associate(Kegiatan::factory()->create());
        //     $jadwal->tempat()->associate(Tempat::factory()->create());
        // });
    }
}
