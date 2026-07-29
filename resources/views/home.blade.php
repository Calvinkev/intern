@extends('layouts.app')

@section('title', 'Home - CODEBASE FOODS')

@section('content')
<!-- Stitch-like Aesthetics: vibrant gradients, glassmorphism, floating animations, modern typography -->
<div class="modern-home">
    <!-- Hero Section -->
    <div class="hero-section position-relative overflow-hidden">
        <div class="hero-bg position-absolute w-100 h-100 top-0 start-0"></div>
        <div class="container position-relative z-index-1 py-5 mb-5">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6 hero-text-container" data-aos="fade-up">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 shadow-sm fw-bold">🚀 The Future of Food Delivery</span>
                    <h1 class="display-3 fw-bolder text-white mb-4">Taste the <br><span class="text-gradient">Extraordinary</span></h1>
                    <p class="lead text-white-50 mb-5 fw-light">Curated culinary experiences from your favorite local chefs, delivered with hyper-speed precision.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('restaurants.index') }}" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg hover-lift text-primary">Explore Menus</a>
                        <a href="#categories" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold hover-lift">Categories</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center position-relative hero-graphics d-none d-lg-block" data-aos="zoom-in" data-aos-delay="200">
                    <div class="glass-card p-5 mx-auto floating-animation">
                        <i class="bi bi-rocket-takeoff text-white" style="font-size: 8rem; filter: drop-shadow(0 0 20px rgba(255,255,255,0.5));"></i>
                    </div>
                    <div class="floating-badge badge-1 glass-badge"><i class="bi bi-star-fill text-warning"></i> Top Rated</div>
                    <div class="floating-badge badge-2 glass-badge"><i class="bi bi-clock-history text-info"></i> Fast Delivery</div>
                </div>
            </div>
        </div>
        <div class="wave-divider position-absolute bottom-0 w-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120"><path fill="#f8f9fa" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,42.7C1120,32,1280,32,1360,32L1440,32L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path></svg>
        </div>
    </div>

    <!-- Categories Section -->
    <div id="categories" class="container mb-5 py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Discover</h6>
            <h2 class="display-5 fw-bold text-dark">Cravings by Category</h2>
        </div>
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-8 g-4 justify-content-center">
            @foreach($categories as $category)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <a href="{{ route('foods.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="category-card text-center border-0 p-3 rounded-4 bg-white hover-lift shadow-sm h-100">
                        <div class="category-icon-wrapper mx-auto mb-3">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="rounded-circle w-100 h-100 object-fit-cover shadow-sm" alt="{{ $category->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200&h=200&fit=crop" class="rounded-circle w-100 h-100 object-fit-cover shadow-sm" alt="{{ $category->name }}">
                            @endif
                        </div>
                        <h6 class="card-title text-dark fw-bold mb-0">{{ $category->name }}</h6>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Restaurants Section -->
    <div class="bg-white py-5 mb-5 rounded-5 shadow-sm">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
                <div>
                    <h6 class="text-primary fw-bold text-uppercase tracking-wider">Premium Selection</h6>
                    <h2 class="display-5 fw-bold text-dark mb-0">Featured Restaurants</h2>
                </div>
                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 hover-lift d-none d-md-inline-block">View All <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($featuredRestaurants as $restaurant)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('restaurants.show', $restaurant->slug) }}" class="text-decoration-none">
                        <div class="restaurant-card card h-100 border-0 rounded-4 overflow-hidden bg-light hover-lift shadow-sm">
                            <div class="position-relative">
                                @if($restaurant->cover_image)
                                    <img src="{{ asset('storage/' . $restaurant->cover_image) }}" class="card-img-top object-fit-cover" style="height: 220px;" alt="{{ $restaurant->name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=600&fit=crop" class="card-img-top object-fit-cover" style="height: 220px;" alt="{{ $restaurant->name }}">
                                @endif
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="glass-badge badge rounded-pill fw-bold"><i class="bi bi-star-fill text-warning me-1"></i> {{ number_format($restaurant->rating, 1) }}</span>
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h4 class="card-title text-dark fw-bold mb-2">{{ $restaurant->name }}</h4>
                                <p class="card-text text-secondary small mb-3">{{ Str::limit($restaurant->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                                    <span class="text-muted small fw-medium">
                                        <i class="bi bi-clock me-1 text-primary"></i> {{ $restaurant->estimated_delivery_time }} min
                                    </span>
                                    <span class="text-primary small fw-bold"><i class="bi bi-bicycle me-1"></i>Fast Delivery</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4 d-md-none">
                 <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 hover-lift w-100">View All</a>
            </div>
        </div>
    </div>

    <!-- Popular Foods Section -->
    <div class="container py-5 mb-5">
        <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
            <div>
                <h6 class="text-danger fw-bold text-uppercase tracking-wider">Trending Now</h6>
                <h2 class="display-5 fw-bold text-dark mb-0">Popular Items</h2>
            </div>
            <a href="{{ route('foods.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 hover-lift d-none d-md-inline-block">Explore Menu <i class="bi bi-arrow-right ms-2"></i></a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($popularFoods as $food)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <a href="{{ route('foods.show', $food->slug) }}" class="text-decoration-none">
                    <div class="food-card card h-100 border-0 rounded-4 overflow-hidden bg-white shadow-sm hover-lift">
                        <div class="position-relative overflow-hidden p-2 pb-0">
                            @if($food->image)
                                <img src="{{ asset('storage/' . $food->image) }}" class="card-img-top rounded-4 object-fit-cover food-img transition" style="height: 200px;" alt="{{ $food->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop" class="card-img-top rounded-4 object-fit-cover food-img transition" style="height: 200px;" alt="{{ $food->name }}">
                            @endif
                            <div class="add-to-cart-btn btn btn-primary rounded-circle shadow-lg position-absolute bottom-0 end-0 m-4 d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                                <i class="bi bi-plus-lg text-white"></i>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title text-dark fw-bold mb-1">{{ $food->name }}</h5>
                            <p class="card-text text-muted small mb-3"><i class="bi bi-shop text-primary me-1"></i>{{ $food->restaurant->name }}</p>
                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                <div>
                                    @if($food->hasDiscount())
                                        <span class="text-decoration-line-through text-muted small me-1">${{ number_format($food->price, 2) }}</span>
                                        <span class="text-danger fw-bolder fs-5">${{ number_format($food->getDiscountedPrice(), 2) }}</span>
                                    @else
                                        <span class="text-dark fw-bolder fs-5">${{ number_format($food->price, 2) }}</span>
                                    @endif
                                </div>
                                <span class="badge bg-light text-dark rounded-pill px-2 py-1 border shadow-sm"><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($food->rating, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4 d-md-none">
             <a href="{{ route('foods.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 hover-lift w-100">Explore Menu</a>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section py-5 my-5 position-relative overflow-hidden rounded-5 mx-3 mx-lg-5 shadow-lg" data-aos="zoom-in">
        <div class="container position-relative z-index-1 py-5">
            <div class="glass-card p-5 text-center mx-auto rounded-5 border border-white border-opacity-25" style="max-width: 800px;">
                <h2 class="display-4 fw-bolder text-white mb-3">Craving Something Special?</h2>
                <p class="lead text-white-50 mb-4 fw-light">Join our community and get access to exclusive deals, lightning fast delivery, and the best food in town.</p>
                @if(auth()->check())
                    <a href="{{ route('restaurants.index') }}" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold shadow hover-lift text-primary">Order Now</a>
                @else
                    <div class="d-flex justify-content-center gap-3 flex-column flex-sm-row">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold shadow hover-lift text-primary">Sign Up Free</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold hover-lift">Login</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="cta-bg position-absolute top-0 start-0 w-100 h-100"></div>
    </div>
</div>
@endsection

@push('styles')
<!-- Include Google Fonts & AOS for Animations -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    body {
        font-family: 'Outfit', sans-serif;
        background-color: #16110f !important;
        color: #fdf5f1 !important;
    }
    
    /* Variables & Gradients */
    :root {
        --primary-gradient: linear-gradient(135deg, #ff6b2b 0%, #e63946 100%);
        --cta-gradient: linear-gradient(45deg, #f9a03f, #f77f00);
        --glass-bg: rgba(35, 25, 22, 0.6);
        --glass-border: rgba(255, 255, 255, 0.05);
    }

    /* Dark Mode Overrides */
    .bg-white { background-color: #241c19 !important; }
    .bg-light { background-color: #2e2420 !important; }
    .text-dark { color: #fdf5f1 !important; }
    .text-muted, .text-secondary { color: #c0aca3 !important; }
    .border-white { border-color: rgba(255,255,255,0.1) !important; }
    .border, .border-top { border-color: #3b2f2b !important; }
    
    .bg-gradient-primary { background: var(--primary-gradient); }
    .text-gradient {
        background: linear-gradient(to right, #ffd166, #ff9f1c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 0 20px rgba(255, 159, 28, 0.3);
    }
    .text-primary { color: #ff6b2b !important; }
    .bg-light-primary { background-color: rgba(255, 107, 43, 0.1); }
    .btn-primary { background: var(--primary-gradient); border: none; color: #fff; }
    .btn-primary:hover { background: linear-gradient(135deg, #e63946 0%, #d62828 100%); box-shadow: 0 10px 20px rgba(230,57,70,0.3); }
    
    /* Utilities */
    .tracking-wider { letter-spacing: 0.1em; }
    .min-vh-75 { min-height: 75vh; }
    .z-index-1 { z-index: 1; }
    .transition { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
    
    /* Hero Section */
    .hero-section {
        background-color: #0f172a;
        margin-top: -1.5rem; /* Offset for default nav margin if any */
        padding-top: 2rem;
    }
    .hero-bg {
        background: radial-gradient(circle at top right, rgba(99,102,241,0.3) 0%, transparent 40%),
                    radial-gradient(circle at bottom left, rgba(168,85,247,0.3) 0%, transparent 40%);
    }
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 50%;
        width: 300px;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    /* Animations */
    .floating-animation {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    
    .floating-badge {
        position: absolute;
        animation: float 5s ease-in-out infinite reverse;
    }
    .badge-1 { top: 15%; right: 10%; }
    .badge-2 { bottom: 20%; left: 10%; animation-delay: 1s; }
    
    .glass-badge {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        padding: 8px 16px;
        color: #1e293b;
        font-weight: bold;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
    
    /* Cards & Interactions */
    .hover-lift {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
    
    .category-icon-wrapper {
        width: 80px;
        height: 80px;
        transition: transform 0.3s ease;
    }
    .category-card:hover .category-icon-wrapper {
        transform: scale(1.1);
    }
    
    .food-card .food-img {
        transform: scale(1);
    }
    .food-card:hover .food-img {
        transform: scale(1.05);
    }
    
    .add-to-cart-btn {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .food-card:hover .add-to-cart-btn {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* CTA */
    .cta-section { background-color: #0f172a; }
    .cta-bg {
        background: url('data:image/svg+xml;utf8,<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="dots" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100%" height="100%" fill="url(%23dots)"/></svg>');
        background-size: cover;
    }
    .cta-section .glass-card {
        width: auto;
        height: auto;
        border-radius: 2rem !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });
    });
</script>
@endpush
