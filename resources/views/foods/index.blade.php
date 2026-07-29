@extends('layouts.app')

@section('title', 'Foods - CODEBASE FOODS')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Menu Items</h1>
        </div>
        <div class="col-md-6">
            <form action="{{ route('foods.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search foods..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('foods.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min Price</label>
                    <input type="number" name="min_price" class="form-control" placeholder="0.00" value="{{ request('min_price') }}" step="0.01">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Max Price</label>
                    <input type="number" name="max_price" class="form-control" placeholder="100.00" value="{{ request('max_price') }}" step="0.01">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Foods Grid -->
    @if($foods->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($foods as $food)
            <div class="col">
                <a href="{{ route('foods.show', $food->slug) }}" class="text-decoration-none">
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
                                <h6 class="card-title text-dark mb-0">{{ $food->name }}</h6>
                                @if($food->is_featured)
                                    <span class="badge bg-warning text-dark">Popular</span>
                                @endif
                            </div>
                            <p class="card-text text-muted small">{{ $food->restaurant->name }}</p>
                            <p class="card-text text-muted small">{{ $food->category->name }}</p>
                            <div class="d-flex justify-content-between align-items-center">
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
                        </div>
                    </div>
                </a>
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
            <h3 class="mt-3">No foods found</h3>
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
