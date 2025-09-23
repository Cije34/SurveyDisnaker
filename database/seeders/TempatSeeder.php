<?php

namespace Database\Seeders;

use App\Models\Tempat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TempatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tempat::factory()->count(5)->create();
    }
}
