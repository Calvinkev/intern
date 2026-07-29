<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Burgers', 'slug' => 'burgers', 'description' => 'Delicious burgers with various toppings', 'sort_order' => 1],
            ['name' => 'Pizza', 'slug' => 'pizza', 'description' => 'Freshly baked pizzas with your favorite toppings', 'sort_order' => 2],
            ['name' => 'Chicken', 'slug' => 'chicken', 'description' => 'Fried, grilled, and roasted chicken dishes', 'sort_order' => 3],
            ['name' => 'Sandwiches', 'slug' => 'sandwiches', 'description' => 'Fresh sandwiches with quality ingredients', 'sort_order' => 4],
            ['name' => 'Salads', 'slug' => 'salads', 'description' => 'Healthy and fresh salad options', 'sort_order' => 5],
            ['name' => 'Pasta', 'slug' => 'pasta', 'description' => 'Italian pasta dishes with various sauces', 'sort_order' => 6],
            ['name' => 'Asian Cuisine', 'slug' => 'asian-cuisine', 'description' => 'Chinese, Thai, and Japanese dishes', 'sort_order' => 7],
            ['name' => 'Desserts', 'slug' => 'desserts', 'description' => 'Sweet treats and desserts', 'sort_order' => 8],
            ['name' => 'Beverages', 'slug' => 'beverages', 'description' => 'Drinks and refreshments', 'sort_order' => 9],
            ['name' => 'Breakfast', 'slug' => 'breakfast', 'description' => 'Morning meals and breakfast items', 'sort_order' => 10],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
