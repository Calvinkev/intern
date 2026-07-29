<?php

namespace App\Services\Cache;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Food;

class RestaurantCacheService
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getRestaurants(array $filters = [])
    {
        $cacheKey = 'restaurants:' . md5(json_encode($filters));
        
        return $this->cacheService->remember($cacheKey, function () use ($filters) {
            $query = Restaurant::active()->with('foods.category');

            if (isset($filters['search'])) {
                $query->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            }

            if (isset($filters['category'])) {
                $category = Category::where('slug', $filters['category'])->first();
                if ($category) {
                    $query->whereHas('foods.category', function ($q) use ($category) {
                        $q->where('id', $category->id);
                    });
                }
            }

            if (isset($filters['featured'])) {
                $query->where('is_featured', true);
            }

            return $query->get();
        }, 1800); // 30 minutes
    }

    public function getRestaurant($slug)
    {
        $cacheKey = 'restaurant:' . $slug;
        
        return $this->cacheService->remember($cacheKey, function () use ($slug) {
            return Restaurant::where('slug', $slug)
                           ->active()
                           ->with('foods.category', 'foods.images')
                           ->first();
        }, 3600); // 1 hour
    }

    public function getCategories()
    {
        $cacheKey = 'categories:all';
        
        return $this->cacheService->rememberForever($cacheKey, function () {
            return Category::active()->ordered()->get();
        });
    }

    public function getFoods(array $filters = [])
    {
        $cacheKey = 'foods:' . md5(json_encode($filters));
        
        return $this->cacheService->remember($cacheKey, function () use ($filters) {
            $query = Food::available()->with('restaurant', 'category', 'images');

            if (isset($filters['search'])) {
                $query->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            }

            if (isset($filters['category'])) {
                $category = Category::where('slug', $filters['category'])->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }

            if (isset($filters['restaurant'])) {
                $query->whereHas('restaurant', function ($q) use ($filters) {
                    $q->where('slug', $filters['restaurant']);
                });
            }

            if (isset($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }

            if (isset($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }

            if (isset($filters['featured'])) {
                $query->where('is_featured', true);
            }

            return $query->get();
        }, 1800); // 30 minutes
    }

    public function getFood($slug)
    {
        $cacheKey = 'food:' . $slug;
        
        return $this->cacheService->remember($cacheKey, function () use ($slug) {
            return Food::where('slug', $slug)
                       ->available()
                       ->with('restaurant', 'category', 'images')
                       ->first();
        }, 3600); // 1 hour
    }

    public function getRestaurantStatistics($restaurantId)
    {
        $cacheKey = 'restaurant_stats:' . $restaurantId;
        
        return $this->cacheService->remember($cacheKey, function () use ($restaurantId) {
            $restaurant = Restaurant::find($restaurantId);
            
            return [
                'total_orders' => $restaurant->orders()->count(),
                'completed_orders' => $restaurant->orders()->where('status', 'delivered')->count(),
                'total_revenue' => $restaurant->orders()->where('status', 'delivered')->sum('total'),
                'total_foods' => $restaurant->foods()->count(),
                'average_rating' => $restaurant->rating,
                'total_reviews' => $restaurant->total_reviews,
            ];
        }, 600); // 10 minutes
    }

    public function clearRestaurantCache($restaurantId = null): void
    {
        if ($restaurantId) {
            $restaurant = Restaurant::find($restaurantId);
            if ($restaurant) {
                $this->cacheService->forget('restaurant:' . $restaurant->slug);
                $this->cacheService->forget('restaurant_stats:' . $restaurantId);
            }
        }
        
        $this->cacheService->forgetPattern('restaurants:*');
        $this->cacheService->forgetPattern('foods:*');
    }

    public function clearFoodCache($foodId = null): void
    {
        if ($foodId) {
            $food = Food::find($foodId);
            if ($food) {
                $this->cacheService->forget('food:' . $food->slug);
            }
        }
        
        $this->cacheService->forgetPattern('foods:*');
    }

    public function clearCategoryCache(): void
    {
        $this->cacheService->forget('categories:all');
    }

    public function clearAll(): void
    {
        $this->cacheService->clearAll();
    }
}
