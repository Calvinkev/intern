<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create system admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@codebase.com',
            'password' => Hash::make('password'),
            'role' => 'system_admin',
            'phone' => '+1234567890',
            'address' => '123 Admin Street',
            'is_active' => true,
        ]);

        // Create restaurant admins
        $restaurantAdmins = [
            [
                'name' => 'John Restaurant',
                'email' => 'john@restaurant.com',
                'password' => Hash::make('password'),
                'role' => 'restaurant_admin',
                'phone' => '+1234567891',
                'address' => '456 Restaurant Ave',
            ],
            [
                'name' => 'Jane Kitchen',
                'email' => 'jane@restaurant.com',
                'password' => Hash::make('password'),
                'role' => 'restaurant_admin',
                'phone' => '+1234567892',
                'address' => '789 Kitchen Blvd',
            ],
            [
                'name' => 'Bob Chef',
                'email' => 'bob@restaurant.com',
                'password' => Hash::make('password'),
                'role' => 'restaurant_admin',
                'phone' => '+1234567893',
                'address' => '321 Chef Lane',
            ],
        ];

        foreach ($restaurantAdmins as $admin) {
            User::create($admin);
        }

        // Create delivery personnel
        $deliveryPersonnel = [
            [
                'name' => 'Mike Delivery',
                'email' => 'mike@delivery.com',
                'password' => Hash::make('password'),
                'role' => 'delivery_personnel',
                'phone' => '+1234567894',
                'address' => '654 Delivery Road',
            ],
            [
                'name' => 'Sarah Rider',
                'email' => 'sarah@delivery.com',
                'password' => Hash::make('password'),
                'role' => 'delivery_personnel',
                'phone' => '+1234567895',
                'address' => '987 Rider Street',
            ],
        ];

        foreach ($deliveryPersonnel as $person) {
            User::create($person);
        }

        // Create customers
        $customers = [
            [
                'name' => 'Alice Customer',
                'email' => 'alice@customer.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1234567896',
                'address' => '111 Customer Ave',
            ],
            [
                'name' => 'Charlie Eater',
                'email' => 'charlie@customer.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1234567897',
                'address' => '222 Eater Street',
            ],
            [
                'name' => 'Diana Foodie',
                'email' => 'diana@customer.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1234567898',
                'address' => '333 Foodie Lane',
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}
