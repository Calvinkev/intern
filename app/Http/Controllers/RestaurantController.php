<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::active();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->whereHas('foods.category', function ($q) use ($category) {
                    $q->where('id', $category->id);
                });
            }
        }

        $restaurants = $query->paginate(12);
        $categories = Category::active()->ordered()->get();

        return view('restaurants.index', compact('restaurants', 'categories'));
    }

    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->active()->firstOrFail();
        $foods = $restaurant->foods()->available()->with('category')->paginate(12);
        $categories = $restaurant->foods()->with('category')->get()->pluck('category')->unique();

        return view('restaurants.show', compact('restaurant', 'foods', 'categories'));
    }
}
