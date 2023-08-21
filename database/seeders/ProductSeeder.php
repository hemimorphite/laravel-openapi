<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($x = 1; $x <= 1000; $x++) {
            $randomArrSize = rand(1, 15);

            $categories = [];
            for ($y = 1; $y <= $randomArrSize; $y++) {
                $randomCategoryId = rand(Category::min('id'), Category::max('id'));
                
                if(in_array($randomCategoryId, $categories)) {
                    continue;
                }

                $categories[] = $randomCategoryId;
            }

            $price = rand(10, 1000)."00";

            $product = Product::create(['name' => 'Product '.$x, 'price' => $price]);

            $product->categories()->sync($categories);
        }
    }
}
