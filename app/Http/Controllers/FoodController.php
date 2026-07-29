<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::available()->with('restaurant', 'category');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->has('restaurant')) {
            $query->whereHas('restaurant', function ($q) use ($request) {
                $q->where('slug', $request->restaurant);
            });
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $foods = $query->paginate(12);
        $categories = Category::active()->ordered()->get();

        return view('foods.index', compact('foods', 'categories'));
    }

    public function show($slug)
    {
        $food = Food::where('slug', $slug)->available()->with('restaurant', 'category', 'images')->firstOrFail();
        $relatedFoods = Food::where('category_id', $food->category_id)
                           ->where('id', '!=', $food->id)
                           ->available()
                           ->take(4)
                           ->get();

        return view('foods.show', compact('food', 'relatedFoods'));
    }
}
