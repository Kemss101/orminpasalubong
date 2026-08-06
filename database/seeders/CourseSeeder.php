<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //2 index array
        $courses = ["Bachelor of Science in Information Technology", 
        "Bachelor of Science in Computer Engineering"];

        $college = ["College of Computer Studies"];

        //Associative Array

        $courses = [
                "course" => "Associate Bachelor of Political Science",
                "collegeName" => "College of Arts and Sciences"
            ];

        //Multi-dimentional Array

        $courses = [
            [
                "course" => "Bachelor of Science in Elementary Education",
                "collegeName" => "College of Teacher Education"
            ],
            [
                "course" => "Bachelor of Science in Civil Engineering",
                "collegeName" => "College of Engineering"
            ],
            [
                "course" => "Bachelor of Science in Mechanical Engineering",
                "collegeName" => "College of Engineering"
            ],
            [
                "course" => "Bachelor of Science in Psychology",
                "collegeName" => "College of Minds"
            ],
            [
                "course" => "Bachelor of Science in Physics",
                "collegeName" => "College of Science"
            ],
            [
                "course" => "Bachelor of Science in Writing",
                "collegeName" => "College of Communication"
            ],
            [
                "course" => "Associate Bachelor of Drawing",
                "collegeName" => "College of Arts"
            ]
        ];

         foreach ($courses as $course){
            DB::table('colleges')->insert([
                'course' => $course['course'],
                'collegeName' => $course['collegeName']
            ]);
         }

    }
}
