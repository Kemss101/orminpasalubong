<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasalubongCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sweets',
                'description' => 'Sweet local treats and dessert-style pasalubong.',
            ],
            [
                'name' => 'Delicacies',
                'description' => 'Traditional local delicacies and kakanin favorites.',
            ],
            [
                'name' => 'Souvenirs',
                'description' => 'Gift items and keepsakes for family and friends.',
            ],
            [
                'name' => 'Wines & Beverages',
                'description' => 'Refreshing local drinks and specialty beverages.',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
