<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\Category;
use App\Models\Notification;
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
            'status' => 'required|in:confirmed,preparing,ready,rejected',
        ]);

        $restaurant = Auth::user()->restaurant;
        $order = $restaurant->orders()->findOrFail($orderId);

        // Define valid status transitions
        $validTransitions = [
            'pending' => ['confirmed', 'rejected'],
            'confirmed' => ['preparing', 'rejected'],
            'preparing' => ['ready', 'rejected'],
            'ready' => ['rejected'],
            'rejected' => [],
        ];

        $currentStatus = $order->status;
        $newStatus = $request->status;

        // Check if the transition is valid
        if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
            return redirect()->back()->with('error', "Cannot change order status from '{$currentStatus}' to '{$newStatus}'.");
        }

        $order->update([
            'status' => $request->status,
            'confirmed_at' => $request->status == 'confirmed' ? now() : $order->confirmed_at,
            'preparing_at' => $request->status == 'preparing' ? now() : $order->preparing_at,
            'ready_at' => $request->status == 'ready' ? now() : $order->ready_at,
        ]);

        // Notify customer of status change
        $statusMessages = [
            'confirmed' => 'Your order has been confirmed and is being prepared.',
            'preparing' => 'Your order is now being prepared.',
            'ready' => 'Your order is ready and waiting for delivery pickup.',
            'rejected' => 'Your order has been rejected by the restaurant.',
        ];

        if (isset($statusMessages[$request->status])) {
            Notification::create([
                'user_id' => $order->user_id,
                'title' => "Order Status Updated: {$request->status}",
                'message' => $statusMessages[$request->status],
                'type' => 'order',
                'order_id' => $order->id,
            ]);
        }

        // Handle rejected orders with proper cleanup
        if ($request->status == 'rejected') {
            $order->update([
                'cancellation_reason' => 'Rejected by restaurant',
                'cancelled_at' => now(),
            ]);

            // Restore stock quantities
            foreach ($order->items as $orderItem) {
                if ($orderItem->food->stock_quantity !== null) {
                    $orderItem->food->increment('stock_quantity', $orderItem->quantity);
                }
            }
        }

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
            'category_id' => 'required_without:new_category|nullable|exists:categories,id',
            'new_category' => 'required_without:category_id|nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'preparation_time' => 'required|integer|min:1',
            'ingredients' => 'nullable|array',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer',
        ]);

        $restaurant = Auth::user()->restaurant;
        
        $categoryId = $request->category_id;
        if ($request->filled('new_category')) {
            $category = Category::create([
                'name' => $request->new_category,
                'slug' => \Str::slug($request->new_category) . '-' . time(),
                'is_active' => true,
            ]);
            $categoryId = $category->id;
        }

        $food = Food::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $categoryId,
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'is_available' => $request->has('is_available'),
            'stock_quantity' => $request->stock_quantity,
            'is_featured' => $request->has('is_featured'),
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
            'category_id' => 'required_without:new_category|nullable|exists:categories,id',
            'new_category' => 'required_without:category_id|nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'preparation_time' => 'required|integer|min:1',
            'ingredients' => 'nullable|array',
            'allergens' => 'nullable|array',
            'calories' => 'nullable|integer',
        ]);

        $restaurant = Auth::user()->restaurant;
        $food = $restaurant->foods()->findOrFail($id);

        $categoryId = $request->category_id;
        if ($request->filled('new_category')) {
            $category = Category::create([
                'name' => $request->new_category,
                'slug' => \Str::slug($request->new_category) . '-' . time(),
                'is_active' => true,
            ]);
            $categoryId = $category->id;
        }

        $food->update([
            'category_id' => $categoryId ?: $food->category_id,
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'is_available' => $request->has('is_available'),
            'stock_quantity' => $request->stock_quantity,
            'is_featured' => $request->has('is_featured'),
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
