<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TahunKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TahunKegiatan::create([
            'tahun' => '2023',
            'is_active' => false,
        ]);

        \App\Models\TahunKegiatan::create([
            'tahun' => '2024',
            'is_active' => false,
        ]);

        \App\Models\TahunKegiatan::create([
            'tahun' => '2025',
            'is_active' => true,
        ]);
    }
}
