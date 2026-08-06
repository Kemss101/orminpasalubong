<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class flightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flight =[
            "flight_id"=>"MBCabc",
            "name"=>"Emmy jane",
            "origin"=>"Pinamalayan",
            "destination"=>"Korea",
        ];
        [
            "flight_id"=>"MBCqwewr",
            "name"=>"janna",
            "origin"=>"socorro",
            "destination"=>"japan",
        ];
        [
             "flight_id"=>"MBCjdfjhf",
            "name"=>"Jane",
            "origin"=>"bongabong",
            "destination"=>"sa imong heart",
        ];
        DB::table('flights')->insert($flight);
    }
}
