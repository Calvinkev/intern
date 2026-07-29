<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SystemAdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalRestaurants = Restaurant::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total');
        
        $activeRestaurants = Restaurant::active()->count();
        $pendingOrders = Order::pending()->count();
        $completedOrders = Order::delivered()->count();
        
        $recentOrders = Order::with('user', 'restaurant')->latest()->take(10)->get();
        $topRestaurants = Restaurant::with('user')->orderByDesc('rating')->take(5)->get();
        $popularFoods = Food::orderByDesc('order_count')->take(5)->get();
        
        $usersByRole = [
            'customers' => User::where('role', 'customer')->count(),
            'restaurant_admins' => User::where('role', 'restaurant_admin')->count(),
            'delivery_personnel' => User::where('role', 'delivery_personnel')->count(),
            'system_admins' => User::where('role', 'system_admin')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRestaurants',
            'totalOrders',
            'totalRevenue',
            'activeRestaurants',
            'pendingOrders',
            'completedOrders',
            'recentOrders',
            'topRestaurants',
            'popularFoods',
            'usersByRole'
        ));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        
        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    public function restaurants()
    {
        $restaurants = Restaurant::with('user')->latest()->paginate(20);
        return view('admin.restaurants', compact('restaurants'));
    }

    public function createRestaurant()
    {
        return view('admin.restaurant-create');
    }

    public function storeRestaurant(Request $request)
    {
        $request->validate([
            'owner_name'              => 'required|string|max:255',
            'owner_email'             => 'required|email|unique:users,email',
            'owner_password'          => 'required|string|min:8',
            'restaurant_name'         => 'required|string|max:255',
            'restaurant_description'  => 'nullable|string',
            'restaurant_address'      => 'required|string|max:500',
            'restaurant_phone'        => 'required|string|max:30',
            'restaurant_email'        => 'required|email',
            'delivery_fee'            => 'nullable|numeric|min:0',
            'min_order_amount'        => 'nullable|numeric|min:0',
            'estimated_delivery_time' => 'nullable|integer|min:1',
        ]);

        // 1. Create the restaurant owner user account
        $user = User::create([
            'name'      => $request->owner_name,
            'email'     => $request->owner_email,
            'password'  => Hash::make($request->owner_password),
            'role'      => 'restaurant_admin',
            'is_active' => true,
        ]);

        // 2. Create the restaurant and link it to the user
        Restaurant::create([
            'user_id'                 => $user->id,
            'name'                    => $request->restaurant_name,
            'slug'                    => Str::slug($request->restaurant_name) . '-' . Str::random(4),
            'description'             => $request->restaurant_description,
            'address'                 => $request->restaurant_address,
            'phone'                   => $request->restaurant_phone,
            'email'                   => $request->restaurant_email,
            'delivery_fee'            => $request->delivery_fee ?? 0,
            'min_order_amount'        => $request->min_order_amount ?? 0,
            'estimated_delivery_time' => $request->estimated_delivery_time ?? 30,
            'status'                  => 'active',
            'rating'                  => 0,
            'total_reviews'           => 0,
        ]);

        return redirect()->route('admin.restaurants')
            ->with('success', "Restaurant '{$request->restaurant_name}' created! Owner login: {$request->owner_email}");
    }

    public function toggleRestaurantStatus($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['status' => $restaurant->status == 'active' ? 'inactive' : 'active']);
        
        return redirect()->back()->with('success', 'Restaurant status updated successfully!');
    }

    public function orders()
    {
        $orders = Order::with('user', 'restaurant')->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function showOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                      ->with('user', 'restaurant', 'items.food', 'payment', 'delivery')
                      ->firstOrFail();
        
        return view('admin.order-details', compact('order'));
    }
}
