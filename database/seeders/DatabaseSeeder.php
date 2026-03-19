<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user from AdminUserSeeder
        $this->call(AdminUserSeeder::class);

        // Optional: Create a sample student user
        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Test Student',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]
        );
    }
}
