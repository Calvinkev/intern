<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = Restaurant::all();
        $categories = Category::all();

        $foods = [];

        // Burger Palace foods
        $burgerPalace = $restaurants->where('slug', 'burger-palace')->first();
        $burgerCategory = $categories->where('slug', 'burgers')->first();
        
        $foods[] = [
            'restaurant_id' => $burgerPalace->id,
            'category_id' => $burgerCategory->id,
            'name' => 'Classic Cheeseburger',
            'slug' => 'classic-cheeseburger-' . time(),
            'description' => 'Juicy beef patty with melted cheese, lettuce, tomato, and special sauce.',
            'price' => 12.99,
            'discount_price' => null,
            'is_available' => true,
            'is_featured' => true,
            'preparation_time' => 15,
            'ingredients' => ['Beef patty', 'Cheddar cheese', 'Lettuce', 'Tomato', 'Onion', 'Special sauce', 'Burger bun'],
            'allergens' => ['Gluten', 'Dairy'],
            'calories' => 650,
            'rating' => 4.6,
            'total_reviews' => 89,
            'order_count' => 245,
        ];

        $foods[] = [
            'restaurant_id' => $burgerPalace->id,
            'category_id' => $burgerCategory->id,
            'name' => 'Bacon Deluxe',
            'slug' => 'bacon-deluxe-' . time(),
            'description' => 'Double beef patty with crispy bacon, cheese, and BBQ sauce.',
            'price' => 15.99,
            'discount_price' => 13.99,
            'is_available' => true,
            'is_featured' => true,
            'preparation_time' => 18,
            'ingredients' => ['Beef patty', 'Bacon', 'Cheddar cheese', 'BBQ sauce', 'Lettuce', 'Burger bun'],
            'allergens' => ['Gluten', 'Dairy'],
            'calories' => 780,
            'rating' => 4.8,
            'total_reviews' => 67,
            'order_count' => 189,
        ];

        $foods[] = [
            'restaurant_id' => $burgerPalace->id,
            'category_id' => $categories->where('slug', 'chicken')->first()->id,
            'name' => 'Crispy Chicken Burger',
            'slug' => 'crispy-chicken-burger-' . time(),
            'description' => 'Golden fried chicken breast with coleslaw and pickles.',
            'price' => 11.99,
            'discount_price' => null,
            'is_available' => true,
            'is_featured' => false,
            'preparation_time' => 14,
            'ingredients' => ['Chicken breast', 'Coleslaw', 'Pickles', 'Mayo', 'Burger bun'],
            'allergens' => ['Gluten', 'Dairy', 'Eggs'],
            'calories' => 520,
            'rating' => 4.4,
            'total_reviews' => 45,
            'order_count' => 123,
        ];

        // Pizza Heaven foods
        $pizzaHeaven = $restaurants->where('slug', 'pizza-heaven')->first();
        $pizzaCategory = $categories->where('slug', 'pizza')->first();

        $foods[] = [
            'restaurant_id' => $pizzaHeaven->id,
            'category_id' => $pizzaCategory->id,
            'name' => 'Margherita Pizza',
            'slug' => 'margherita-pizza-' . time(),
            'description' => 'Classic pizza with fresh mozzarella, tomatoes, and basil.',
            'price' => 14.99,
            'discount_price' => null,
            'is_available' => true,
            'is_featured' => true,
            'preparation_time' => 20,
            'ingredients' => ['Pizza dough', 'Tomato sauce', 'Fresh mozzarella', 'Basil', 'Olive oil'],
            'allergens' => ['Gluten', 'Dairy'],
            'calories' => 680,
            'rating' => 4.7,
            'total_reviews' => 156,
            'order_count' => 312,
        ];

        $foods[] = [
            'restaurant_id' => $pizzaHeaven->id,
            'category_id' => $pizzaCategory->id,
            'name' => 'Pepperoni Supreme',
            'slug' => 'pepperoni-supreme-' . time(),
            'description' => 'Loaded with pepperoni, mozzarella, and Italian herbs.',
            'price' => 16.99,
            'discount_price' => null,
            'is_available' => true,
            'is_featured' => true,
            'preparation_time' => 22,
            'ingredients' => ['Pizza dough', 'Tomato sauce', 'Mozzarella', 'Pepperoni', 'Italian herbs'],
            'allergens' => ['Gluten', 'Dairy'],
            'calories' => 750,
            'rating' => 4.5,
            'total_reviews' => 98,
            'order_count' => 267,
        ];

        $foods[] = [
            'restaurant_id' => $pizzaHeaven->id,
            'category_id' => $categories->where('slug', 'pasta')->first()->id,
            'name' => 'Spaghetti Carbonara',
            'slug' => 'spaghetti-carbonara-' . time(),
            'description' => 'Creamy pasta with bacon, eggs, and parmesan.',
            'price' => 13.99,
            'discount_price' => 11.99,
            'is_available' => true,
            'is_featured' => false,
            'preparation_time' => 18,
            'ingredients' => ['Spaghetti', 'Bacon', 'Eggs', 'Parmesan', 'Cream', 'Black pepper'],
            'allergens' => ['Gluten', 'Dairy', 'Eggs'],
            'calories' => 620,
            'rating' => 4.6,
            'total_reviews' => 72,
            'order_count' => 198,
        ];

        // Asian Fusion Kitchen foods
        $asianFusion = $restaurants->where('slug', 'asian-fusion-kitchen')->first();
        $asianCategory = $categories->where('slug', 'asian-cuisine')->first();

        $foods[] = [
            'restaurant_id' => $asianFusion->id,
            'category_id' => $asianCategory->id,
            'name' => 'Sweet and Sour Chicken',
            'slug' => 'sweet-sour-chicken-' . time(),
            'description' => 'Crispy chicken in tangy sweet and sour sauce with vegetables.',
            'price' => 13.99,
            'discount_price' => null,
            'is_available' => true,
            'is_featured' => true,
            'preparation_time' => 20,
            'ingredients' => ['Chicken', 'Bell peppers', 'Pineapple', 'Onion', 'Sweet and sour sauce'],
            'allergens' => ['Soy', 'Gluten'],
            'calories' => 550,
            'rating' => 4.4,
            'total_reviews' => 56,
            'order_count' => 145,
        ];

        $foods[] = [
            'restaurant_id' => $asianFusion->id,
            'category_id' => $asianCategory->id,
            'name' => 'Pad Thai',
            'slug' => 'pad-thai-' . time(),
            'description' => 'Stir-fried rice noodles with shrimp, tofu, and peanuts.',
            'price' => 12.99,
            'discount_price' => null,
            'is_available' => true,
            'is_featured' => false,
            'preparation_time' => 18,
            'ingredients' => ['Rice noodles', 'Shrimp', 'Tofu', 'Eggs', 'Peanuts', 'Bean sprouts'],
            'allergens' => ['Peanuts', 'Soy', 'Gluten', 'Shellfish'],
            'calories' => 580,
            'rating' => 4.5,
            'total_reviews' => 43,
            'order_count' => 112,
        ];

        $foods[] = [
            'restaurant_id' => $asianFusion->id,
            'category_id' => $categories->where('slug', 'salads')->first()->id,
            'name' => 'Asian Salad',
            'slug' => 'asian-salad-' . time(),
            'description' => 'Fresh greens with ginger dressing and crispy wontons.',
            'price' => 9.99,
            'discount_price' => null,
            'is_available' => true,
            'is_featured' => false,
            'preparation_time' => 10,
            'ingredients' => ['Mixed greens', 'Cucumber', 'Carrots', 'Ginger dressing', 'Wontons'],
            'allergens' => ['Soy', 'Gluten'],
            'calories' => 320,
            'rating' => 4.2,
            'total_reviews' => 28,
            'order_count' => 67,
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }
    }
}
