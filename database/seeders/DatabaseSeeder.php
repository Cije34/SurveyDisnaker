<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'), // Ensure to hash the password
        ]);
    }
}
