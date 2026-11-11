<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Use updateOrCreate to prevent duplicate entry errors if seeded multiple times
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'adminadmin', // Plain text for vulnerable mode SQLi testing
            ]
        );
    }
}