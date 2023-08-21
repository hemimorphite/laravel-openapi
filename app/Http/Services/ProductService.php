<?php

namespace App\Http\Services;

use App\Models\Product;

class ProductService {

    public function store(array $data)
    {
        $categories = [];
        foreach ($data['categories'] as $category) {
            $categories[] = $category['id'];
        }
        $product = Product::create($data);
        $product->categories()->sync($categories);

        $product = Product::with('categories')->find($product->id);

        return $product;
    }

    public function update(array $data, Product $product): Product
    {
        
        $product->update($data);
        
        if(array_key_exists("categories", $data)) {
            $categories = [];
            foreach ($data['categories'] as $category) {
                $categories[] = $category['id'];
            }
            $product->categories()->sync($categories);
        }
        
        $product = Product::with('categories')->find($product->id);
        
        return $product;
    }
}