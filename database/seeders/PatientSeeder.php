<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run()
    {
        Patient::create([
            'patient_id' => 'P001',
            'name' => 'John Doe',
            'age' => 30,
            'gender' => 'Male',
            'contact' => '1234567890',
            'status' => 'active',
        ]);

        Patient::create([
            'patient_id' => 'P002',
            'name' => 'Jane Smith',
            'age' => 25,
            'gender' => 'Female',
            'contact' => '0987654321',
            'status' => 'active',
        ]);

        Patient::create([
            'patient_id' => 'P003',
            'name' => 'Alice Johnson',
            'age' => 40,
            'gender' => 'Female',
            'contact' => '1122334455',
            'status' => 'inactive',
        ]);

        Patient::create([
            'patient_id' => 'P004',
            'name' => 'Bob Brown',
            'age' => 35,
            'gender' => 'Male',
            'contact' => '5566778899',
            'status' => 'active',
        ]);
    }
}