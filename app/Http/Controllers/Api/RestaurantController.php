<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::active()->with('foods.category');

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

        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        $restaurants = $query->paginate(15);

        return response()->json($restaurants);
    }

    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
                              ->active()
                              ->with('foods.category', 'foods.images')
                              ->firstOrFail();

        return response()->json($restaurant);
    }
}
