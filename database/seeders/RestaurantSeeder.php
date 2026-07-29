<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurantAdmins = User::where('role', 'restaurant_admin')->get();
        
        $restaurants = [
            [
                'user_id' => $restaurantAdmins[0]->id,
                'name' => 'Burger Palace',
                'slug' => 'burger-palace',
                'description' => 'The best burgers in town, made with fresh ingredients and love.',
                'address' => '123 Main Street',
                'phone' => '+1234567890',
                'email' => 'info@burgerpalace.com',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'status' => 'active',
                'opening_time' => '10:00:00',
                'closing_time' => '23:00:00',
                'delivery_fee' => 3.99,
                'min_order_amount' => 15.00,
                'estimated_delivery_time' => 30,
                'is_featured' => true,
                'rating' => 4.5,
                'total_reviews' => 150,
            ],
            [
                'user_id' => $restaurantAdmins[1]->id,
                'name' => 'Pizza Heaven',
                'slug' => 'pizza-heaven',
                'description' => 'Authentic Italian pizza with fresh toppings and homemade sauce.',
                'address' => '456 Oak Avenue',
                'phone' => '+1234567891',
                'email' => 'orders@pizzaheaven.com',
                'latitude' => 40.7580,
                'longitude' => -73.9855,
                'status' => 'active',
                'opening_time' => '11:00:00',
                'closing_time' => '00:00:00',
                'delivery_fee' => 4.99,
                'min_order_amount' => 20.00,
                'estimated_delivery_time' => 35,
                'is_featured' => true,
                'rating' => 4.7,
                'total_reviews' => 230,
            ],
            [
                'user_id' => $restaurantAdmins[2]->id,
                'name' => 'Asian Fusion Kitchen',
                'slug' => 'asian-fusion-kitchen',
                'description' => 'Delicious Asian cuisine blending flavors from across the continent.',
                'address' => '789 Pine Road',
                'phone' => '+1234567892',
                'email' => 'hello@asianfusion.com',
                'latitude' => 40.7484,
                'longitude' => -73.9857,
                'status' => 'active',
                'opening_time' => '09:00:00',
                'closing_time' => '22:00:00',
                'delivery_fee' => 2.99,
                'min_order_amount' => 12.00,
                'estimated_delivery_time' => 25,
                'is_featured' => false,
                'rating' => 4.3,
                'total_reviews' => 95,
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::create($restaurant);
        }
    }
}
