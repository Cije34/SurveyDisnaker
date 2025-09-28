<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Mentor;
use Illuminate\Database\Seeder;

class JadwalMentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $jadwals = Jadwal::all();
        $mentors = Mentor::all();

        if ($jadwals->isEmpty() || $mentors->isEmpty()) {
            $this->command->info('Tidak ada jadwal atau mentor untuk di-seed');

            return;
        }

        // Attach mentors ke jadwals yang ada
        foreach ($jadwals as $jadwal) {
            // Attach 1-3 mentor random ke setiap jadwal
            $randomMentors = $mentors->random(rand(1, min(3, $mentors->count())));
            $jadwal->mentors()->attach($randomMentors->pluck('id')->toArray());
        }

        $this->command->info('JadwalMentorSeeder completed successfully');
    }
}
