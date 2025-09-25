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
        \App\Models\TahunKegiatan::factory()->count(1)->create();
    }
}
