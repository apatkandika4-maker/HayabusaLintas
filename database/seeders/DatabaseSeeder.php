<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(KalbarSeeds::class);

        User::firstOrCreate(
            ['email' => 'admin@travelkalbar.id'],
            [
                'name' => 'Admin Hayabusa Lintas',
                'phone' => '081234567890',
                'role' => 'admin',
                'password' => 'admin123',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@travelkalbar.id'],
            [
                'name' => 'Pengguna Demo',
                'phone' => '081234567891',
                'role' => 'user',
                'password' => 'user123',
            ]
        );
    }
}
