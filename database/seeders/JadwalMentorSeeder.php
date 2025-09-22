<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Mentor;
use App\Models\Kegiatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JadwalMentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $jadwals = Jadwal::all();
    $mentors = Mentor::all();

    foreach ($mentors as $mentor) {
        $mentor->jadwals()->attach(
            $jadwals->random(rand(1, 3))->pluck('id')->toArray()
        );
    }

    }
}
