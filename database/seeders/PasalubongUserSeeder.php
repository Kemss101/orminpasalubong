<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasalubongUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ormin.com'],
            [
                'name' => 'Store Administrator',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'seller@ormin.com'],
            [
                'name' => 'Store Seller',
                'password' => Hash::make('password'),
                'user_type' => 'seller',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@ormin.com'],
            [
                'name' => 'Loyal Customer',
                'password' => Hash::make('password'),
                'user_type' => 'customer',
            ]
        );
    }
}
