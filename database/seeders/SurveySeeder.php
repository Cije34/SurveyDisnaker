<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kegiatans = \App\Models\Kegiatan::all();

        foreach ($kegiatans as $kegiatan) {
            Survey::factory()->create([
                'kegiatan_id' => $kegiatan->id,
                'type' => 'choice',
                'pertanyaan' => 'Seberapa puas Anda dengan kegiatan ini?',
            ]);

            Survey::factory()->create([
                'kegiatan_id' => $kegiatan->id,
                'type' => 'text',
                'pertanyaan' => 'Masukan atau saran untuk kegiatan ini',
            ]);
        }
    }
}
