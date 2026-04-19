<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    // Use updateOrCreate to avoid "Duplicate Entry" errors if you run it twice
    User::updateOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ]
    );
    }
}
