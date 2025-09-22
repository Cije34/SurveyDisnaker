<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MentorSeeder;
use Database\Seeders\PenjabSeeder;
use Database\Seeders\SurveySeeder;
use Database\Seeders\TempatSeeder;
use Database\Seeders\JawabanSeeder;
use Database\Seeders\PesertaSeeder;
use Database\Seeders\KegiatanSeeder;
use Database\Seeders\TahunKegiatanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TahunKegiatanSeeder::class,
            KegiatanSeeder::class,
            PenjabSeeder::class,
            MentorSeeder::class,
            TempatSeeder::class,
            PesertaSeeder::class,
            SurveySeeder::class,
            JawabanSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'), // Ensure to hash the password
        ]);
    }
}
