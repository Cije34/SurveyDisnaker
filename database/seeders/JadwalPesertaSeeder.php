<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Peserta;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JadwalPesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = Jadwal::all();
    $peserta = Peserta::all();

    foreach ($peserta as $pesertas) {
        $pesertas->jadwals()->attach(
            $jadwals->random(rand(1, 3))->pluck('id')->toArray()
        );
    }
    }
}
