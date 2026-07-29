<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantAdminController extends Controller
{
    public function dashboard()
    {
        $restaurant = Auth::user()->restaurant;
        if (!$restaurant) {
            return redirect()->route('restaurant.create')->with('error', 'Please create your restaurant profile first.');
        }

        $pendingOrders = $restaurant->orders()->pending()->count();
        $confirmedOrders = $restaurant->orders()->confirmed()->count();
        $preparingOrders = $restaurant->orders()->preparing()->count();
        $readyOrders = $restaurant->orders()->ready()->count();
        $completedOrders = $restaurant->orders()->delivered()->count();
        $totalRevenue = $restaurant->orders()->delivered()->sum('total');
        $recentOrders = $restaurant->orders()->with('user')->latest()->take(5)->get();
        $popularFoods = $restaurant->foods()->orderByDesc('order_count')->take(5)->get();

        return view('restaurant-admin.dashboard', compact(
            'restaurant',
            'pendingOrders',
            'confirmedOrders',
            'preparingOrders',
            'readyOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders',
            'popularFoods'
        ));
    }

    public function orders()
    {
        $restaurant = Auth::user()->restaurant;
        $orders = $restaurant->orders()->with('user', 'items')->latest()->paginate(20);

        return view('restaurant-admin.orders', compact('orders', 'restaurant'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,picked_up,rejected',
        ]);

        $restaurant = Auth::user()->restaurant;
        $order = $restaurant->orders()->findOrFail($orderId);

        $order->update([
            'status' => $request->status,
            'confirmed_at' => $request->status == 'confirmed' ? now() : $order->confirmed_at,
            'preparing_at' => $request->status == 'preparing' ? now() : $order->preparing_at,
            'ready_at' => $request->status == 'ready' ? now() : $order->ready_at,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function foods()
    {
        $restaurant = Auth::user()->restaurant;
        $foods = $restaurant->foods()->with('category')->latest()->paginate(20);
        $categories = Category::active()->get();

        return view('restaurant-admin.foods', compact('foods', 'categories', 'restaurant'));
    }

    public function createFood()
    {
        $restaurant = Auth::user()->restaurant;
        $categories = Category::active()->get();

        return view('restaurant-admin.foods-create', compact('categories', 'restaurant'));
    }

    public function storeFood(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'preparation_time' => 'required|integer|min:1',
            'ingredients' => 'nullable|array',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer',
        ]);

        $restaurant = Auth::user()->restaurant;
        
        $food = Food::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'is_available' => $request->is_available ?? true,
            'is_featured' => $request->is_featured ?? false,
            'preparation_time' => $request->preparation_time,
            'ingredients' => $request->ingredients,
            'allergens' => $request->allergens,
            'calories' => $request->calories,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('foods', 'public');
            $food->update(['image' => $path]);
        }

        return redirect()->route('restaurant.admin.foods')->with('success', 'Food item created successfully!');
    }

    public function editFood($id)
    {
        $restaurant = Auth::user()->restaurant;
        $food = $restaurant->foods()->findOrFail($id);
        $categories = Category::active()->get();

        return view('restaurant-admin.foods-edit', compact('food', 'categories', 'restaurant'));
    }

    public function updateFood(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'preparation_time' => 'required|integer|min:1',
            'ingredients' => 'nullable|array',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer',
        ]);

        $restaurant = Auth::user()->restaurant;
        $food = $restaurant->foods()->findOrFail($id);

        $food->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'is_available' => $request->is_available ?? true,
            'is_featured' => $request->is_featured ?? false,
            'preparation_time' => $request->preparation_time,
            'ingredients' => $request->ingredients,
            'allergens' => $request->allergens,
            'calories' => $request->calories,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('foods', 'public');
            $food->update(['image' => $path]);
        }

        return redirect()->route('restaurant.admin.foods')->with('success', 'Food item updated successfully!');
    }

    public function deleteFood($id)
    {
        $restaurant = Auth::user()->restaurant;
        $food = $restaurant->foods()->findOrFail($id);
        $food->delete();

        return redirect()->route('restaurant.admin.foods')->with('success', 'Food item deleted successfully!');
    }

    public function profile()
    {
        $restaurant = Auth::user()->restaurant;

        return view('restaurant-admin.profile', compact('restaurant'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'delivery_fee' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'estimated_delivery_time' => 'required|integer|min:1',
        ]);

        $restaurant = Auth::user()->restaurant;

        $restaurant->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'delivery_fee' => $request->delivery_fee,
            'min_order_amount' => $request->min_order_amount,
            'estimated_delivery_time' => $request->estimated_delivery_time,
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('restaurants', 'public');
            $restaurant->update(['logo' => $path]);
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('restaurants', 'public');
            $restaurant->update(['cover_image' => $path]);
        }

        return redirect()->route('restaurant.admin.profile')->with('success', 'Restaurant profile updated successfully!');
    }
}
