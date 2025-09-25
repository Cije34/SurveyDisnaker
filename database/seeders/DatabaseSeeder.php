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
            RolePermissionSeeder::class,
            // TahunKegiatanSeeder::class,
            KegiatanSeeder::class,
            PenjabSeeder::class,
            MentorSeeder::class,
            TempatSeeder::class,
            PesertaSeeder::class,
            SurveySeeder::class,
            JawabanSeeder::class,
            JadwalSeeder::class,
            JadwalMentorSeeder::class,
            JadwalPesertaSeeder::class,
        ]);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Ensure to hash the password
        ]);

        $admin->assignRole('admin');
    }
}
