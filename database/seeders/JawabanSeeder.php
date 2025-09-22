<?php

namespace Database\Seeders;

use App\Models\Jawaban;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jawaban::factory()->count(10)->create();
    }
}
