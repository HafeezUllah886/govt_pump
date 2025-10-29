<?php

namespace Database\Seeders;

use App\Models\Vehicles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class vehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        vehicles::create([
            'r_no'             => "ABC-1234",
            'department_id'    => 1,
            'type'              => "SUV",
        ]);
        vehicles::create([
            'r_no'             => "XYZ-1234",
            'department_id'    => 2,
            'type'              => "SUV",
        ]);
    }
}
