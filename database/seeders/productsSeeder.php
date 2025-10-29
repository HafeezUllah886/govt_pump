<?php

namespace Database\Seeders;

use App\Models\product_dc;
use App\Models\product_units;
use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
             ['name' => "Petrol", 'unit' => 'Ltr', 'price' => 256.34, 'status' => 'Active'],
             ['name' => "Diesel", 'unit' => 'Ltr', 'price' => 245.76, 'status' => 'Active'],
             ['name' => "Engine Oil 4Ltr", 'unit' => 'Nos', 'price' => 4350, 'status' => 'Active'],
        ];
        products::insert($data);
    }
}
