@extends('layouts.app')

@section('title', 'Restaurants - CODEBASE FOODS')

@section('content')

{{-- Page Header --}}
<div class="py-5" style="background: linear-gradient(135deg, #1a0e0c 0%, #241c19 80%, #16110f 100%); border-bottom: 1px solid #3b2f2b;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <h1 class="fw-bolder display-5 mb-2">
                    <i class="bi bi-shop me-2" style="color:#ff6b2b;"></i> Restaurants
                </h1>
                <p style="color:#c0aca3;">Discover amazing food from the best local restaurants</p>
            </div>
            <div class="col-md-6">
                <form action="{{ route('restaurants.index') }}" method="GET">
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text" style="background:#2e2420; border:1px solid #3b2f2b; border-right:none;">
                            <i class="bi bi-search" style="color:#ff6b2b;"></i>
                        </span>
                        <input type="text" name="search" class="form-control py-3"
                            placeholder="Search restaurants..."
                            value="{{ request('search') }}"
                            style="border-left:none; border-radius:0;">
                        <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius:0 0.5rem 0.5rem 0;">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    {{-- Category Filter Pills --}}
    <div class="mb-5">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('restaurants.index') }}"
               class="btn rounded-pill px-4 py-2 fw-medium {{ !request('category') ? 'btn-primary' : '' }}"
               style="{{ !request('category') ? '' : 'background:#2e2420; color:#c0aca3; border:1px solid #3b2f2b;' }}">
                <i class="bi bi-grid me-1"></i> All
            </a>
            @foreach($categories as $category)
            <a href="{{ route('restaurants.index', ['category' => $category->slug]) }}"
               class="btn rounded-pill px-4 py-2 fw-medium {{ request('category') == $category->slug ? 'btn-primary' : '' }}"
               style="{{ request('category') == $category->slug ? '' : 'background:#2e2420; color:#c0aca3; border:1px solid #3b2f2b;' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Restaurants Grid --}}
    @if($restaurants->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($restaurants as $restaurant)
            <div class="col">
                <a href="{{ route('restaurants.show', $restaurant->slug) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 hover-lift overflow-hidden" style="box-shadow: 0 4px 20px rgba(0,0,0,0.4);">

                        {{-- Cover Image --}}
                        <div class="position-relative overflow-hidden" style="height:200px;">
                            @if($restaurant->cover_image)
                                <img src="{{ asset('storage/' . $restaurant->cover_image) }}"
                                    class="w-100 h-100 object-fit-cover"
                                    style="transition: transform 0.4s ease;"
                                    alt="{{ $restaurant->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                                    style="background: linear-gradient(135deg, #2e2420, #1a1412);">
                                    <i class="bi bi-shop" style="font-size:4rem; color:#3b2f2b;"></i>
                                </div>
                            @endif
                            {{-- Badges overlay --}}
                            <div class="position-absolute top-0 start-0 p-3 d-flex gap-2 flex-wrap">
                                @if($restaurant->is_featured)
                                    <span class="badge rounded-pill fw-bold px-3" style="background: linear-gradient(135deg,#ffd166,#f9a03f); color:#1a0e0c;">
                                        ⭐ Featured
                                    </span>
                                @endif
                                @if($restaurant->isOpen())
                                    <span class="badge rounded-pill fw-bold px-3" style="background:rgba(74,222,128,0.15); border:1px solid #16a34a; color:#4ade80;">
                                        <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Open
                                    </span>
                                @else
                                    <span class="badge rounded-pill fw-bold px-3" style="background:rgba(248,113,113,0.15); border:1px solid #e63946; color:#f87171;">
                                        Closed
                                    </span>
                                @endif
                            </div>
                            {{-- Rating badge --}}
                            <div class="position-absolute top-0 end-0 p-3">
                                <span class="badge rounded-pill fw-bold px-3 py-2" style="background:rgba(0,0,0,0.6); backdrop-filter:blur(8px);">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    {{ number_format($restaurant->rating, 1) }}
                                    <span class="text-white-50 fw-normal">({{ $restaurant->total_reviews }})</span>
                                </span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-1">{{ $restaurant->name }}</h5>
                            <p class="small mb-3" style="color:#c0aca3;">{{ Str::limit($restaurant->description, 80) }}</p>
                            <p class="small mb-3" style="color:#c0aca3;">
                                <i class="bi bi-geo-alt me-1" style="color:#ff6b2b;"></i> {{ $restaurant->address }}
                            </p>
                            <hr style="border-color:#3b2f2b;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small fw-medium" style="color:#c0aca3;">
                                    <i class="bi bi-clock text-warning me-1"></i> {{ $restaurant->estimated_delivery_time }} min
                                </span>
                                <span class="small fw-medium" style="color:#c0aca3;">
                                    <i class="bi bi-truck me-1" style="color:#ff6b2b;"></i> Shs {{ number_format($restaurant->delivery_fee, 0) }} delivery
                                </span>
                            </div>
                        </div>

                    </div>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-5">
            {{ $restaurants->links() }}
        </div>

    @else
        <div class="text-center py-5">
            <i class="bi bi-shop" style="font-size:5rem; color:#3b2f2b;"></i>
            <h3 class="mt-3 fw-bold">No restaurants found</h3>
            <p style="color:#c0aca3;">Try adjusting your search or filters.</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-primary rounded-pill px-5 mt-2">Clear Filters</a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card:hover img { transform: scale(1.05); }
</style>
@endpush

