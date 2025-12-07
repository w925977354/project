<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Call AdminUserSeeder to create test accounts (admin and regular user).
     */
    public function run(): void
    {
        // Create admin and regular users for testing
        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}
