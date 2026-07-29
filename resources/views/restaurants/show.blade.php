@extends('layouts.app')

@section('title', $restaurant->name . ' - CodeBase Food Ordering')

@section('content')
<div class="container py-5">
    <!-- Restaurant Header -->
    <div class="row mb-4">
        <div class="col-md-4">
            @if($restaurant->cover_image)
                <img src="{{ asset('storage/' . $restaurant->cover_image) }}" class="img-fluid rounded shadow" alt="{{ $restaurant->name }}">
            @else
                <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 250px;">
                    <i class="bi bi-shop text-muted" style="font-size: 5rem;"></i>
                </div>
            @endif
        </div>
        <div class="col-md-8">
            <h1>{{ $restaurant->name }}</h1>
            <p class="text-muted">{{ $restaurant->description }}</p>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><i class="bi bi-geo-alt"></i> {{ $restaurant->address }}</p>
                    <p><i class="bi bi-telephone"></i> {{ $restaurant->phone }}</p>
                </div>
                <div class="col-md-6">
                    <p><i class="bi bi-star-fill text-warning"></i> {{ number_format($restaurant->rating, 1) }} ({{ $restaurant->total_reviews }} reviews)</p>
                    <p><i class="bi bi-clock"></i> {{ $restaurant->estimated_delivery_time }} min delivery</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-success">{{ $restaurant->status }}</span>
                @if($restaurant->is_featured)
                    <span class="badge bg-warning text-dark">Featured</span>
                @endif
                @if($restaurant->isOpen())
                    <span class="badge bg-primary">Open Now</span>
                @else
                    <span class="badge bg-secondary">Closed</span>
                @endif
            </div>
            <p class="mt-2 text-muted small">
                <i class="bi bi-truck"></i> Delivery: ${{ number_format($restaurant->delivery_fee, 2) }} | 
                <i class="bi bi-currency-dollar"></i> Min order: ${{ number_format($restaurant->min_order_amount, 2) }}
            </p>
        </div>
    </div>

    <!-- Categories Filter -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('restaurants.show', $restaurant->slug) }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
            @foreach($categories as $category)
            <a href="{{ route('restaurants.show', [$restaurant->slug, 'category' => $category->slug]) }}" class="btn {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Foods Grid -->
    @if($foods->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($foods as $food)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    @if($food->image)
                        <img src="{{ asset('storage/' . $food->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $food->name }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-egg-fried text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">{{ $food->name }}</h6>
                            @if($food->is_featured)
                                <span class="badge bg-warning text-dark">Popular</span>
                            @endif
                        </div>
                        <p class="card-text text-muted small">{{ Str::limit($food->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            @if($food->hasDiscount())
                                <div>
                                    <span class="text-decoration-line-through text-muted small">${{ number_format($food->price, 2) }}</span>
                                    <span class="text-danger fw-bold">${{ number_format($food->getDiscountedPrice(), 2) }}</span>
                                </div>
                            @else
                                <span class="fw-bold">${{ number_format($food->price, 2) }}</span>
                            @endif
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-star-fill"></i> {{ number_format($food->rating, 1) }}
                            </span>
                        </div>
                        @if(auth()->check() && auth()->user()->isCustomer())
                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="hidden" name="food_id" value="{{ $food->id }}">
                                <input type="number" name="quantity" value="1" min="1" max="99" class="form-control form-control-sm" style="width: 70px;">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="bi bi-cart-plus"></i> Add
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $foods->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-egg-fried text-muted" style="font-size: 4rem;"></i>
            <h3 class="mt-3">No menu items available</h3>
            <p class="text-muted">This restaurant hasn't added any menu items yet.</p>
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
