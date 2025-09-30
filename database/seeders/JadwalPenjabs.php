<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Penjab;
use Illuminate\Database\Seeder;

class JadwalPenjabs extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = Jadwal::all();
        $penjabs = Penjab::all();

        foreach ($penjabs as $penjab) {
            $penjab->jadwals()->attach(
                $jadwals->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
        //
    }
}
