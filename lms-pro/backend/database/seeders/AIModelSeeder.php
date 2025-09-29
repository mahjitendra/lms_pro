<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AIModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This is a placeholder. In a real application, you would have a more sophisticated way
        // of managing AI models. For now, we'll just insert some dummy data.
        if (!DB::table('ai_models')->exists()) {
            DB::table('ai_models')->insert([
                [
                    'name' => 'General Purpose Classifier',
                    'description' => 'A model for general image classification.',
                    'type' => 'classification',
                    'version' => '1.0.0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Object Detector',
                    'description' => 'A model for detecting objects in images.',
                    'type' => 'object-detection',
                    'version' => '1.0.0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}