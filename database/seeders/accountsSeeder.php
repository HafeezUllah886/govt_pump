<?php

namespace Database\Seeders;

use App\Models\accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class accountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        accounts::create([
            'title'             => "Test Department",
            'focal_person'      => "Abc Khan",
            'contact'           => "0312-1234567",
            'status'            => "Active",
        ]);
        accounts::create([
            'title'             => "Test Department 2",
            'focal_person'      => "Xyz Khan",
            'contact'           => "0312-1234567",
            'status'            => "Active",
        ]);
    }
}
