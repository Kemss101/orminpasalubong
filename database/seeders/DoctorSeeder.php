<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        Doctor::create([
            'doctor_id' => 1,
            'name' => 'Dr. John Smith',
            'specialization' => 'Cardiology',
        ]);

        Doctor::create([
            'doctor_id' => 2,
            'name' => 'Dr. Jane Doe',
            'specialization' => 'Pediatrics',
        ]);

        Doctor::create([
            'doctor_id' => 3,
            'name' => 'Dr. Emily Johnson',
            'specialization' => 'Dermatology',
        ]);

        Doctor::create([
            'doctor_id' => 4,
            'name' => 'Dr. Michael Brown',
            'specialization' => 'Orthopedics',
        ]);

        Doctor::create([
            'doctor_id' => 5,
            'name' => 'Dr. Sarah Davis',
            'specialization' => 'Neurology',
        ]);
    }
}