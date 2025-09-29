<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This is a placeholder. In a real application, you would use a roles and permissions package
        // or create a roles table and model. For now, we'll just insert some dummy data.
        if (!DB::table('roles')->exists()) {
             DB::table('roles')->insert([
                ['name' => 'student', 'guard_name' => 'web'],
                ['name' => 'instructor', 'guard_name' => 'web'],
                ['name' => 'admin', 'guard_name' => 'web'],
            ]);
        }
    }
}