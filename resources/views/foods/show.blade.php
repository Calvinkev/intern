@extends('layouts.app')

@section('title', $food->name . ' - CODEBASE FOODS')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $food->restaurant->slug) }}">{{ $food->restaurant->name }}</a></li>
            <li class="breadcrumb-item active">{{ $food->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 mb-4">
            @if($food->image)
                <img src="{{ asset('storage/' . $food->image) }}" class="img-fluid rounded shadow" alt="{{ $food->name }}">
            @else
                <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="bi bi-egg-fried text-muted" style="font-size: 6rem;"></i>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <h1>{{ $food->name }}</h1>
            <p class="text-muted mb-3">{{ $food->restaurant->name }}</p>
            
            <div class="mb-3">
                @if($food->hasDiscount())
                    <h3 class="text-danger">${{ number_format($food->getDiscountedPrice(), 2) }}</h3>
                    <span class="text-decoration-line-through text-muted">${{ number_format($food->price, 2) }}</span>
                    <span class="badge bg-success ms-2">{{ round((($food->price - $food->getDiscountedPrice()) / $food->price) * 100) }}% OFF</span>
                @else
                    <h3>${{ number_format($food->price, 2) }}</h3>
                @endif
            </div>

            <div class="mb-3">
                <span class="badge bg-warning text-dark">
                    <i class="bi bi-star-fill"></i> {{ number_format($food->rating, 1) }} ({{ $food->total_reviews }} reviews)
                </span>
                @if($food->is_featured)
                    <span class="badge bg-primary">Popular</span>
                @endif
                @if($food->is_available)
                    <span class="badge bg-success">Available</span>
                @else
                    <span class="badge bg-secondary">Unavailable</span>
                @endif
            </div>

            <p class="mb-3">{{ $food->description }}</p>

            <div class="mb-3">
                <strong>Category:</strong> {{ $food->category->name }}
            </div>

            <div class="mb-3">
                <strong>Preparation Time:</strong> {{ $food->preparation_time }} minutes
            </div>

            @if($food->ingredients)
                <div class="mb-3">
                    <strong>Ingredients:</strong>
                    <p class="text-muted">{{ implode(', ', $food->ingredients) }}</p>
                </div>
            @endif

            @if($food->allergens)
                <div class="mb-3">
                    <strong>Allergens:</strong>
                    <p class="text-danger">{{ implode(', ', $food->allergens) }}</p>
                </div>
            @endif

            @if($food->calories)
                <div class="mb-3">
                    <strong>Calories:</strong> {{ $food->calories }} kcal
                </div>
            @endif

            @if(auth()->check() && auth()->user()->isCustomer() && $food->is_available)
                <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-2 mt-4">
                    @csrf
                    <input type="hidden" name="food_id" value="{{ $food->id }}">
                    <input type="number" name="quantity" value="1" min="1" max="99" class="form-control" style="width: 100px;">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
            @elseif(!auth()->check())
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-4 w-100">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </a>
            @else
                <button class="btn btn-secondary btn-lg mt-4 w-100" disabled>
                    <i class="bi bi-cart-x"></i> Currently Unavailable
                </button>
            @endif
        </div>
    </div>

    <!-- Related Foods -->
    @if($relatedFoods->count() > 0)
        <div class="mt-5">
            <h3>Related Items</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">
                @foreach($relatedFoods as $relatedFood)
                <div class="col">
                    <a href="{{ route('foods.show', $relatedFood->slug) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                            @if($relatedFood->image)
                                <img src="{{ asset('storage/' . $relatedFood->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $relatedFood->name }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="bi bi-egg-fried text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title text-dark">{{ $relatedFood->name }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($relatedFood->hasDiscount())
                                        <span class="text-danger fw-bold">${{ number_format($relatedFood->getDiscountedPrice(), 2) }}</span>
                                    @else
                                        <span class="fw-bold">${{ number_format($relatedFood->price, 2) }}</span>
                                    @endif
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill"></i> {{ number_format($relatedFood->rating, 1) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.hover-shadow {
    transition: box-shadow 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.transition {
    transition: all 0.3s ease;
}
</style>
@endpush
