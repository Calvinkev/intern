<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantAdminController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\SystemAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{slug}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/foods', [FoodController::class, 'index'])->name('foods.index');
Route::get('/foods/{slug}', [FoodController::class, 'show'])->name('foods.show');

// Customer routes (require authentication)
Route::middleware(['auth', 'customer'])->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/place', [OrderController::class, 'place'])->name('orders.place');
    Route::post('/orders/{orderNumber}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Restaurant Admin routes
Route::middleware(['auth', 'restaurant.admin'])->prefix('restaurant-admin')->name('restaurant.admin.')->group(function () {
    Route::get('/dashboard', [RestaurantAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [RestaurantAdminController::class, 'orders'])->name('orders');
    Route::post('/orders/{orderId}/status', [RestaurantAdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::get('/foods', [RestaurantAdminController::class, 'foods'])->name('foods');
    Route::get('/foods/create', [RestaurantAdminController::class, 'createFood'])->name('foods.create');
    Route::post('/foods', [RestaurantAdminController::class, 'storeFood'])->name('foods.store');
    Route::get('/foods/{id}/edit', [RestaurantAdminController::class, 'editFood'])->name('foods.edit');
    Route::put('/foods/{id}', [RestaurantAdminController::class, 'updateFood'])->name('foods.update');
    Route::delete('/foods/{id}', [RestaurantAdminController::class, 'deleteFood'])->name('foods.delete');
    Route::get('/profile', [RestaurantAdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [RestaurantAdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('/busy-mode', [RestaurantAdminController::class, 'setBusyMode'])->name('busy-mode');
    Route::post('/clear-busy-mode', [RestaurantAdminController::class, 'clearBusyMode'])->name('clear-busy-mode');
});

// Delivery Personnel routes
Route::middleware(['auth', 'delivery.personnel'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');
    Route::post('/deliveries/{id}/accept', [DeliveryController::class, 'acceptDelivery'])->name('accept');
    Route::post('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus'])->name('update-status');
    Route::get('/deliveries/{id}', [DeliveryController::class, 'showDelivery'])->name('show');
});

// System Admin routes
Route::middleware(['auth', 'system.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [SystemAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [SystemAdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/toggle-status', [SystemAdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/restaurants', [SystemAdminController::class, 'restaurants'])->name('restaurants');
    Route::get('/restaurants/create', [SystemAdminController::class, 'createRestaurant'])->name('restaurants.create');
    Route::post('/restaurants', [SystemAdminController::class, 'storeRestaurant'])->name('restaurants.store');
    Route::post('/restaurants/{id}/toggle-status', [SystemAdminController::class, 'toggleRestaurantStatus'])->name('restaurants.toggle-status');
    Route::get('/orders', [SystemAdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [SystemAdminController::class, 'showOrder'])->name('orders.show');
});

// Dashboard route (will be role-based)
Route::get('/dashboard', function () {
    if (auth()->user()->isCustomer()) {
        return redirect()->route('orders.index');
    } elseif (auth()->user()->isRestaurantAdmin()) {
        return redirect()->route('restaurant.admin.dashboard');
    } elseif (auth()->user()->isDeliveryPersonnel()) {
        return redirect()->route('delivery.dashboard');
    } elseif (auth()->user()->isSystemAdmin()) {
        return redirect()->route('admin.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
