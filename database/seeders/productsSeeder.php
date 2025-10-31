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
             ['code' => "001", 'name' => "Petrol", 'unit' => 'Ltr', 'price' => 256.34, 'status' => 'Active'],
             ['code' => "002", 'name' => "Diesel", 'unit' => 'Ltr', 'price' => 245.76, 'status' => 'Active'],
             ['code' => "003", 'name' => "Engine Oil 4Ltr", 'unit' => 'Nos', 'price' => 4350, 'status' => 'Active'],
        ];
        products::insert($data);
    }
}
