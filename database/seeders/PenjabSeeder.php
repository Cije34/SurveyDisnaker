<?php

namespace Database\Seeders;

use App\Models\Penjab;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenjabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingAdmins = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->whereDoesntHave('penjab')
            ->limit(5)
            ->get();

        $needed = max(0, 5 - $existingAdmins->count());

        $newAdmins = $needed > 0
            ? User::factory()->count($needed)->create()
            : collect();

        $newAdmins->each(fn (User $user) => $user->assignRole('admin'));

        $admins = $existingAdmins->concat($newAdmins)->values();

        Penjab::factory()
            ->count(5)
            ->sequence(fn ($sequence) => [
                'user_id' => $admins[$sequence->index]->id,
            ])
            ->create();
    }
}
