<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredRestaurants = Restaurant::active()->featured()->take(6)->get();
        $categories = Category::active()->ordered()->take(8)->get();
        $popularFoods = Food::available()->popular()->take(8)->get();

        return view('home', compact('featuredRestaurants', 'categories', 'popularFoods'));
    }
}
