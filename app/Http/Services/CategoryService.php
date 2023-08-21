<?php

namespace App\Http\Services;

use App\Models\Category;

class CategoryService {

    public function store(array $data): Category
    {
        $category = Category::create($data);

        return $category;
    }

    public function update(array $data, Category $category): Category
    {
        $category->update($data);

        return $category;
    }
}