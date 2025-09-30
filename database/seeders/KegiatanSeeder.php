<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun2023 = \App\Models\TahunKegiatan::where('tahun', '2023')->first();
        $tahun2024 = \App\Models\TahunKegiatan::where('tahun', '2024')->first();
        $tahun2025 = \App\Models\TahunKegiatan::where('tahun', '2025')->first();

        Kegiatan::create([
            'nama_kegiatan' => 'Pelatihan Digital Marketing',
            'tahun_kegiatan_id' => $tahun2023->id,
        ]);

        Kegiatan::create([
            'nama_kegiatan' => 'Workshop UI/UX Design',
            'tahun_kegiatan_id' => $tahun2023->id,
        ]);

        Kegiatan::create([
            'nama_kegiatan' => 'Seminar Teknologi AI',
            'tahun_kegiatan_id' => $tahun2024->id,
        ]);

        Kegiatan::create([
            'nama_kegiatan' => 'Bootcamp Web Development',
            'tahun_kegiatan_id' => $tahun2024->id,
        ]);

        Kegiatan::create([
            'nama_kegiatan' => 'Pelatihan Data Science',
            'tahun_kegiatan_id' => $tahun2025->id,
        ]);

        Kegiatan::create([
            'nama_kegiatan' => 'Workshop Cybersecurity',
            'tahun_kegiatan_id' => $tahun2025->id,
        ]);
    }
}
