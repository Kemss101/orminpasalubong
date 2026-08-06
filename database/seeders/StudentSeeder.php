<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = ["janna", "jane", "joy"];
        foreach ($name as $n) {
            DB::table('students')->insert([
                'name' => $n,
                'email' => strtolower($n) . '@example.com',
                'age' => rand(18, 25)
            ]);
        }

        //Associative Array(single insert)
         $student = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 25
        ];
        DB::table('students')->insert($student);

        //
         //Multi-dimensional Array
        $students = [
            [
                'name' => 'janna',
                'email' => 'janna@example.com',
                'age' => 25
            ],
            [
                'name' => 'Jane ',
                'email' => 'jane@example.com',
                'age' => 30
            ]
        ];
        DB::table('students')->insert($students);
    }
}
