<?php

namespace Database\Seeders;

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
         $mentors = Mentor::all();
        $kegiatans = Kegiatan::all();

        foreach ($mentors as $mentor) {
            $mentor->jadwals()->attach(
                $kegiatans->random(rand(1, 3))->pluck('id')->toArray(),
                ['tanggal' => now()->addDays(rand(1, 10))]
            );
        }
    }
}
