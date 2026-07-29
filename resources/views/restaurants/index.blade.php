@extends('layouts.app')

@section('title', 'Restaurants - CodeBase Food Ordering')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Restaurants</h1>
        </div>
        <div class="col-md-6">
            <form action="{{ route('restaurants.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search restaurants..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <!-- Categories Filter -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('restaurants.index') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
            @foreach($categories as $category)
            <a href="{{ route('restaurants.index', ['category' => $category->slug]) }}" class="btn {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Restaurants Grid -->
    @if($restaurants->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($restaurants as $restaurant)
            <div class="col">
                <a href="{{ route('restaurants.show', $restaurant->slug) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                        @if($restaurant->cover_image)
                            <img src="{{ asset('storage/' . $restaurant->cover_image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $restaurant->name }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title text-dark mb-0">{{ $restaurant->name }}</h5>
                                @if($restaurant->is_featured)
                                    <span class="badge bg-warning text-dark">Featured</span>
                                @endif
                            </div>
                            <p class="card-text text-muted small mb-2">{{ Str::limit($restaurant->description, 80) }}</p>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-geo-alt"></i> {{ $restaurant->address }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill"></i> {{ number_format($restaurant->rating, 1) }} ({{ $restaurant->total_reviews }})
                                </span>
                                <span class="text-muted small">
                                    <i class="bi bi-clock"></i> {{ $restaurant->estimated_delivery_time }} min
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="text-muted small">
                                    <i class="bi bi-truck"></i> ${{ number_format($restaurant->delivery_fee, 2) }} delivery
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $restaurants->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
            <h3 class="mt-3">No restaurants found</h3>
            <p class="text-muted">Try adjusting your search or filters.</p>
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
