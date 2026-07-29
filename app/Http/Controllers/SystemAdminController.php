<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
