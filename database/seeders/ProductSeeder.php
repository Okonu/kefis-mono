<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $numberOfProducts = 15;

        for ($i = 1; $i <= $numberOfProducts; ++$i) {
            Product::create([
                'name' => "Product $i",
                'inventory' => 50,
            ]);
        }
    }
}
