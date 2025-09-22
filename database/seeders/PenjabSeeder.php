<?php

namespace Database\Seeders;

use App\Models\Penjab;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenjabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penjab::factory()
            ->count(10)
            ->create();
    }
}
