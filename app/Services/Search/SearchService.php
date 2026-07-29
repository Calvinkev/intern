<?php

namespace App\Services\Search;

use App\Models\Restaurant;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class SearchService
{
    public function search(string $query, array $filters = [])
    {
        $results = [
            'restaurants' => [],
            'foods' => [],
            'categories' => [],
        ];

        if (empty($query)) {
            return $results;
        }

        // Search restaurants
        $results['restaurants'] = $this->searchRestaurants($query, $filters);

        // Search foods
        $results['foods'] = $this->searchFoods($query, $filters);

        // Search categories
        $results['categories'] = $this->searchCategories($query);

        return $results;
    }

    public function searchRestaurants(string $query, array $filters = [])
    {
        $searchQuery = Restaurant::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('address', 'like', '%' . $query . '%');
            });

        if (isset($filters['category'])) {
            $category = Category::where('slug', $filters['category'])->first();
            if ($category) {
                $searchQuery->whereHas('foods.category', function ($q) use ($category) {
                    $q->where('id', $category->id);
                });
            }
        }

        if (isset($filters['min_rating'])) {
            $searchQuery->where('rating', '>=', $filters['min_rating']);
        }

        if (isset($filters['featured'])) {
            $searchQuery->where('is_featured', $filters['featured']);
        }

        return $searchQuery->with('foods.category')->get();
    }

    public function searchFoods(string $query, array $filters = [])
    {
        $searchQuery = Food::available()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhereJsonContains('ingredients', $query);
            });

        if (isset($filters['category'])) {
            $category = Category::where('slug', $filters['category'])->first();
            if ($category) {
                $searchQuery->where('category_id', $category->id);
            }
        }

        if (isset($filters['restaurant'])) {
            $searchQuery->whereHas('restaurant', function ($q) use ($filters) {
                $q->where('slug', $filters['restaurant']);
            });
        }

        if (isset($filters['min_price'])) {
            $searchQuery->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $searchQuery->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['vegetarian'])) {
            $searchQuery->where('is_vegetarian', $filters['vegetarian']);
        }

        if (isset($filters['featured'])) {
            $searchQuery->where('is_featured', $filters['featured']);
        }

        return $searchQuery->with('restaurant', 'category')->get();
    }

    public function searchCategories(string $query)
    {
        return Category::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->ordered()
            ->get();
    }

    public function autocomplete(string $query, int $limit = 10)
    {
        $results = [];

        if (empty($query)) {
            return $results;
        }

        // Restaurant suggestions
        $restaurants = Restaurant::active()
            ->where('name', 'like', '%' . $query . '%')
            ->limit($limit)
            ->get(['id', 'name', 'slug'])
            ->map(function ($restaurant) {
                return [
                    'type' => 'restaurant',
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'slug' => $restaurant->slug,
                    'url' => route('restaurants.show', $restaurant->slug),
                ];
            });

        // Food suggestions
        $foods = Food::available()
            ->where('name', 'like', '%' . $query . '%')
            ->limit($limit)
            ->with('restaurant')
            ->get()
            ->map(function ($food) {
                return [
                    'type' => 'food',
                    'id' => $food->id,
                    'name' => $food->name,
                    'slug' => $food->slug,
                    'restaurant' => $food->restaurant->name,
                    'url' => route('foods.show', $food->slug),
                ];
            });

        // Category suggestions
        $categories = Category::active()
            ->where('name', 'like', '%' . $query . '%')
            ->limit($limit)
            ->get(['id', 'name', 'slug'])
            ->map(function ($category) {
                return [
                    'type' => 'category',
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'url' => route('foods.index', ['category' => $category->slug]),
                ];
            });

        return array_merge($restaurants->toArray(), $foods->toArray(), $categories->toArray());
    }

    public function advancedSearch(array $criteria)
    {
        $query = $criteria['query'] ?? '';
        $filters = $criteria['filters'] ?? [];

        $results = $this->search($query, $filters);

        // Apply sorting
        if (isset($criteria['sort'])) {
            $sortField = $criteria['sort'];
            $sortOrder = $criteria['order'] ?? 'asc';

            switch ($sortField) {
                case 'price':
                    $results['foods'] = $results['foods']->sortBy('price', SORT_REGULAR, $sortOrder === 'desc');
                    break;
                case 'rating':
                    $results['restaurants'] = $results['restaurants']->sortBy('rating', SORT_REGULAR, $sortOrder === 'desc');
                    break;
                case 'popularity':
                    $results['foods'] = $results['foods']->sortByDesc('order_count');
                    break;
                case 'name':
                    $results['restaurants'] = $results['restaurants']->sortBy('name', SORT_NATURAL, $sortOrder === 'desc');
                    $results['foods'] = $results['foods']->sortBy('name', SORT_NATURAL, $sortOrder === 'desc');
                    break;
            }
        }

        return $results;
    }
}
