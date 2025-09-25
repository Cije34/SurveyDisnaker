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
        Kegiatan::factory()
            ->count(100)
            ->create([
                'tahun_kegiatan_id' => \App\Models\TahunKegiatan::factory(),
            ]);
    }
}
