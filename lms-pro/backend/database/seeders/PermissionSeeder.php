<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This is a placeholder. In a real application, you would use a roles and permissions package.
        // For now, we'll just insert some dummy data.
        if (!DB::table('permissions')->exists()) {
            DB::table('permissions')->insert([
                // Course Permissions
                ['name' => 'create courses', 'guard_name' => 'web'],
                ['name' => 'edit courses', 'guard_name' => 'web'],
                ['name' => 'delete courses', 'guard_name' => 'web'],

                // User Permissions
                ['name' => 'manage users', 'guard_name' => 'web'],
            ]);
        }
    }
}