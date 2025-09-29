<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            // In a real app, you would assign the 'admin' role here
        ]);

        // Create a default instructor user
        User::factory()->create([
            'name' => 'Instructor User',
            'email' => 'instructor@example.com',
            'password' => Hash::make('password'),
            // In a real app, you would assign the 'instructor' role here
        ]);

        // Create a default student user
        User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            // In a real app, you would assign the 'student' role here
        ]);

        // Create additional random users
        User::factory()->count(10)->create();
    }
}