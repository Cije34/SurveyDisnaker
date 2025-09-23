<?php

namespace Database\Seeders;

use App\Models\Mentor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mentor::factory()->count(10)->create();
    }
}
