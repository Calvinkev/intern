@extends('layouts.app')
@section('title', 'CODEBASE FOODS — Taste the Extraordinary')

@section('content')
<div class="home-wrapper">

{{-- ══════════════ HERO ══════════════ --}}
<section class="hero-section position-relative overflow-hidden">
    <div class="hero-orbs"></div>
    <div class="container position-relative" style="z-index:2; padding: 6rem 1rem 5rem;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-up">
                <span class="hero-pill mb-4 d-inline-flex align-items-center gap-2">
                    <span class="pulse-dot"></span> Live & Accepting Orders
                </span>
                <h1 class="display-2 fw-bolder lh-1 mb-4" style="color:#fdf5f1;">
                    Food that<br>
                    <span class="text-flame">Fires Up</span><br>
                    Your Cravings
                </h1>
                <p class="lead mb-5 fw-light" style="color:#c0aca3; max-width:500px;">
                    Curated culinary experiences from your favourite local chefs, delivered blazing fast to your door.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('restaurants.index') }}" class="btn btn-hero-primary btn-lg rounded-pill px-5 py-3 fw-bold">
                        <i class="bi bi-shop me-2"></i> Explore Restaurants
                    </a>
                    <a href="#popular" class="btn btn-hero-outline btn-lg rounded-pill px-5 py-3 fw-bold">
                        Popular Now
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="d-flex gap-4 mt-5 flex-wrap">
                    <div class="trust-badge">
                        <i class="bi bi-lightning-charge-fill" style="color:#ffd166;"></i>
                        <span>30-min delivery</span>
                    </div>
                    <div class="trust-badge">
                        <i class="bi bi-shield-check" style="color:#4ade80;"></i>
                        <span>Safe & hygienic</span>
                    </div>
                    <div class="trust-badge">
                        <i class="bi bi-star-fill" style="color:#ff6b2b;"></i>
                        <span>Top-rated chefs</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-flex justify-content-center position-relative" data-aos="zoom-in" data-aos-delay="200">
                <div class="hero-visual-ring floating-animation">
                    <i class="bi bi-egg-fried" style="font-size:7rem; color:#ff6b2b; filter:drop-shadow(0 0 30px rgba(255,107,43,0.5));"></i>
                </div>
                <div class="floating-pill pill-1">
                    <i class="bi bi-star-fill text-warning me-1"></i> 4.9 Rating
                </div>
                <div class="floating-pill pill-2">
                    <i class="bi bi-clock me-1" style="color:#60a5fa;"></i> 25 min avg
                </div>
                <div class="floating-pill pill-3">
                    <i class="bi bi-people-fill me-1" style="color:#4ade80;"></i> 2k+ Customers
                </div>
            </div>
        </div>
    </div>
    {{-- Wave --}}
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" preserveAspectRatio="none">
            <path fill="#16110f" d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z"/>
        </svg>
    </div>
</section>

{{-- ══════════════ CATEGORIES ══════════════ --}}
<section id="categories" class="py-5 mt-2">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-label">Browse</span>
            <h2 class="display-5 fw-bolder">Cravings by Category</h2>
        </div>
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3 justify-content-center">
            @foreach($categories as $cat)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 40 }}">
                <a href="{{ route('foods.index', ['category' => $cat->slug]) }}" class="text-decoration-none">
                    <div class="cat-card text-center p-3 h-100">
                        <div class="cat-img-wrap mx-auto mb-3">
                            @if($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}" class="rounded-circle w-100 h-100 object-fit-cover" alt="{{ $cat->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200&h=200&fit=crop" class="rounded-circle w-100 h-100 object-fit-cover" alt="{{ $cat->name }}">
                            @endif
                        </div>
                        <span class="fw-bold small" style="color:#fdf5f1;">{{ $cat->name }}</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════ FEATURED RESTAURANTS ══════════════ --}}
<section class="py-5 my-3" style="background: linear-gradient(180deg, #16110f 0%, #1a0f0c 100%);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
            <div>
                <span class="section-label">Premium Selection</span>
                <h2 class="display-5 fw-bolder mb-0">Featured Restaurants</h2>
            </div>
            <a href="{{ route('restaurants.index') }}" class="btn btn-ghost d-none d-md-inline-flex align-items-center gap-2">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($featuredRestaurants as $r)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <a href="{{ route('restaurants.show', $r->slug) }}" class="text-decoration-none">
                    <div class="rest-card h-100 hover-lift">
                        <div class="rest-img-wrap position-relative overflow-hidden">
                            @if($r->cover_image)
                                <img src="{{ asset('storage/' . $r->cover_image) }}" class="rest-img" alt="{{ $r->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=400&fit=crop" class="rest-img" alt="{{ $r->name }}">
                            @endif
                            <div class="rest-overlay"></div>
                            <span class="rest-rating">
                                <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($r->rating, 1) }}
                            </span>
                        </div>
                        <div class="rest-body p-4">
                            <h4 class="fw-bold mb-1">{{ $r->name }}</h4>
                            <p class="small mb-3" style="color:#c0aca3;">{{ Str::limit($r->description, 75) }}</p>
                            <div class="d-flex justify-content-between" style="border-top:1px solid #3b2f2b; padding-top:0.75rem;">
                                <span class="small" style="color:#c0aca3;">
                                    <i class="bi bi-clock text-warning me-1"></i>{{ $r->estimated_delivery_time }} min
                                </span>
                                <span class="small fw-bold" style="color:#ff6b2b;">
                                    <i class="bi bi-bicycle me-1"></i>Fast Delivery
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('restaurants.index') }}" class="btn btn-ghost w-100">View All Restaurants</a>
        </div>
    </div>
</section>

{{-- ══════════════ POPULAR FOODS ══════════════ --}}
<section id="popular" class="py-5 my-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
            <div>
                <span class="section-label" style="color:#e63946;">🔥 Trending</span>
                <h2 class="display-5 fw-bolder mb-0">Popular Items</h2>
            </div>
            <a href="{{ route('foods.index') }}" class="btn btn-ghost d-none d-md-inline-flex align-items-center gap-2">
                Explore Menu <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($popularFoods as $food)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <a href="{{ route('foods.show', $food->slug) }}" class="text-decoration-none">
                    <div class="food-card h-100 hover-lift">
                        <div class="food-img-wrap position-relative overflow-hidden">
                            @if($food->image)
                                <img src="{{ asset('storage/' . $food->image) }}" class="food-img" alt="{{ $food->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop" class="food-img" alt="{{ $food->name }}">
                            @endif
                            <div class="food-add-btn">
                                <i class="bi bi-plus-lg"></i>
                            </div>
                        </div>
                        <div class="food-body p-4">
                            <h5 class="fw-bold mb-1">{{ $food->name }}</h5>
                            <p class="small mb-3" style="color:#c0aca3;">
                                <i class="bi bi-shop me-1" style="color:#ff6b2b;"></i>{{ $food->restaurant->name }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($food->hasDiscount())
                                        <span class="text-decoration-line-through small me-1" style="color:#c0aca3;">Shs {{ number_format($food->price, 0) }}</span>
                                        <span class="fw-bolder fs-5" style="color:#e63946;">Shs {{ number_format($food->getDiscountedPrice(), 2) }}</span>
                                    @else
                                        <span class="fw-bolder fs-5" style="color:#fdf5f1;">Shs {{ number_format($food->price, 0) }}</span>
                                    @endif
                                </div>
                                <span class="rating-pill">
                                    <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($food->rating, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('foods.index') }}" class="btn btn-ghost w-100">Explore Full Menu</a>
        </div>
    </div>
</section>

{{-- ══════════════ HOW IT WORKS ══════════════ --}}
<section class="py-5 my-3" style="background:#1a0f0c;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">Simple Process</span>
            <h2 class="display-5 fw-bolder">How It Works</h2>
        </div>
        <div class="row g-4 text-center">
            @php $steps = [
                ['icon'=>'bi-search','color'=>'#ff6b2b','title'=>'Browse','desc'=>'Explore restaurants & menus near you'],
                ['icon'=>'bi-cart-plus','color'=>'#ffd166','title'=>'Order','desc'=>'Add your favourite items to cart'],
                ['icon'=>'bi-credit-card','color'=>'#4ade80','title'=>'Pay','desc'=>'Pay securely with multiple options'],
                ['icon'=>'bi-bicycle','color'=>'#60a5fa','title'=>'Enjoy','desc'=>'Get it delivered hot to your door'],
            ]; @endphp
            @foreach($steps as $i => $step)
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="how-card p-4">
                    <div class="how-icon mb-3" style="color:{{ $step['color'] }}; background:{{ $step['color'] }}22;">
                        <i class="bi {{ $step['icon'] }} fs-2"></i>
                    </div>
                    <div class="how-step-num mb-2" style="color:{{ $step['color'] }};">Step {{ $i + 1 }}</div>
                    <h5 class="fw-bold mb-2">{{ $step['title'] }}</h5>
                    <p class="small mb-0" style="color:#c0aca3;">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════ CTA BANNER ══════════════ --}}
<section class="py-5 my-5 mx-3 mx-lg-5 cta-banner rounded-5 overflow-hidden position-relative" data-aos="zoom-in">
    <div class="cta-dots"></div>
    <div class="container position-relative text-center py-5" style="z-index:2;">
        <h2 class="display-4 fw-bolder text-white mb-3">Craving Something?</h2>
        <p class="lead mb-5 fw-light" style="color:rgba(255,255,255,0.7); max-width:600px; margin:0 auto 2rem;">
            Join thousands of happy customers. Get exclusive deals, lightning-fast delivery, and the best food in town.
        </p>
        @auth
            <a href="{{ route('restaurants.index') }}" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold shadow" style="color:#e63946;">
                <i class="bi bi-shop me-2"></i>Order Now
            </a>
        @else
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold shadow" style="color:#e63946;">
                    <i class="bi bi-person-plus me-2"></i>Sign Up Free
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold">
                    Login
                </a>
            </div>
        @endauth
    </div>
</section>

</div>
@endsection

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
/* ── Hero ── */
.hero-section {
    background: radial-gradient(ellipse at top right, #2a0f08 0%, #16110f 50%, #0d0806 100%);
    padding-bottom: 0;
}
.hero-orbs {
    position:absolute; inset:0; z-index:1;
    background:
        radial-gradient(circle at 80% 20%, rgba(255,107,43,.18) 0%, transparent 40%),
        radial-gradient(circle at 20% 80%, rgba(230,57,70,.15) 0%, transparent 40%);
}
.hero-wave { line-height:0; }
.hero-wave svg { display:block; width:100%; height:80px; }

.hero-pill {
    background: rgba(255,107,43,.15);
    border: 1px solid rgba(255,107,43,.4);
    color: #ff6b2b;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: .85rem;
    font-weight: 600;
}
.pulse-dot {
    width:8px; height:8px; border-radius:50%;
    background:#4ade80;
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{ box-shadow:0 0 0 0 rgba(74,222,128,.5); } 50%{ box-shadow:0 0 0 6px rgba(74,222,128,0); } }

.text-flame {
    background: linear-gradient(135deg, #ff9f1c, #ff6b2b, #e63946);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.btn-hero-primary {
    background: linear-gradient(135deg, #ff6b2b, #e63946);
    color: #fff; border:none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 30px rgba(230,57,70,.35);
}
.btn-hero-primary:hover { transform:translateY(-3px); box-shadow:0 14px 40px rgba(230,57,70,.45); color:#fff; }
.btn-hero-outline {
    background: transparent;
    color: #fdf5f1;
    border: 1px solid rgba(255,255,255,.2);
    transition: all 0.3s ease;
}
.btn-hero-outline:hover { background:rgba(255,255,255,.08); color:#fff; border-color:rgba(255,255,255,.4); }
.btn-ghost {
    background:rgba(255,255,255,.05);
    color:#c0aca3; border:1px solid #3b2f2b;
    border-radius:50px; padding:8px 20px;
    transition:all .2s;
}
.btn-ghost:hover { background:#2e2420; color:#fdf5f1; }

.trust-badge { display:flex; align-items:center; gap:8px; color:#c0aca3; font-size:.875rem; }

.hero-visual-ring {
    width:320px; height:320px; border-radius:50%;
    background:rgba(255,107,43,.08);
    border:2px solid rgba(255,107,43,.2);
    display:flex; align-items:center; justify-content:center;
    box-shadow: 0 0 80px rgba(255,107,43,.15);
}
.floating-animation { animation:float 6s ease-in-out infinite; }
@keyframes float { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-18px);} }

.floating-pill {
    position:absolute;
    background:rgba(255,255,255,.07);
    backdrop-filter:blur(12px);
    border:1px solid rgba(255,255,255,.1);
    border-radius:50px; padding:8px 16px;
    color:#fdf5f1; font-size:.85rem; font-weight:600;
    animation: float 5s ease-in-out infinite reverse;
}
.pill-1 { top:10%; right:0;  animation-delay:0s; }
.pill-2 { bottom:15%; right:5%; animation-delay:1s; }
.pill-3 { top:50%; left:-5%; animation-delay:2s; }

/* ── Section Labels ── */
.section-label {
    display:inline-block; font-size:.78rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.12em;
    color:#ff6b2b; margin-bottom:.5rem;
}
.section-header h2 { color:#fdf5f1; }

/* ── Category Cards ── */
.cat-card {
    background:#241c19;
    border:1px solid #3b2f2b;
    border-radius:1rem;
    transition: all .25s ease;
    cursor:pointer;
}
.cat-card:hover { transform:translateY(-6px); border-color:#ff6b2b; box-shadow:0 12px 30px rgba(0,0,0,.4); }
.cat-img-wrap { width:72px; height:72px; }
.cat-card:hover .cat-img-wrap img { transform:scale(1.08); }
.cat-img-wrap img { transition:transform .3s ease; }

/* ── Restaurant Cards ── */
.rest-card { background:#241c19; border:1px solid #3b2f2b; border-radius:1rem; overflow:hidden; }
.rest-img-wrap { height:200px; }
.rest-img { width:100%; height:100%; object-fit:cover; transition:transform .4s ease; }
.rest-card:hover .rest-img { transform:scale(1.06); }
.rest-overlay { position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.5) 0%, transparent 60%); }
.rest-rating {
    position:absolute; bottom:12px; left:12px;
    background:rgba(0,0,0,.6); backdrop-filter:blur(8px);
    border-radius:50px; padding:4px 12px;
    font-size:.8rem; font-weight:700; color:#fdf5f1;
}
.rest-body { color:#fdf5f1; }

/* ── Food Cards ── */
.food-card { background:#241c19; border:1px solid #3b2f2b; border-radius:1rem; overflow:hidden; }
.food-img-wrap { height:190px; overflow:hidden; position:relative; }
.food-img { width:100%; height:100%; object-fit:cover; transition:transform .4s ease; }
.food-card:hover .food-img { transform:scale(1.07); }
.food-add-btn {
    position:absolute; bottom:12px; right:12px;
    width:42px; height:42px; border-radius:50%;
    background:linear-gradient(135deg,#ff6b2b,#e63946);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:1.1rem;
    opacity:0; transform:translateY(10px);
    transition:all .25s cubic-bezier(.34,1.56,.64,1);
    box-shadow:0 6px 20px rgba(230,57,70,.4);
}
.food-card:hover .food-add-btn { opacity:1; transform:translateY(0); }
.food-body { color:#fdf5f1; }
.rating-pill {
    background:#2e2420; border:1px solid #3b2f2b;
    border-radius:50px; padding:4px 10px; font-size:.8rem; color:#fdf5f1;
}

/* ── How it Works ── */
.how-card { border-radius:1rem; transition:all .25s; }
.how-card:hover { background:#2e2420; transform:translateY(-4px); }
.how-icon { width:64px; height:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto; }
.how-step-num { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; }

/* ── CTA Banner ── */
.cta-banner {
    background: linear-gradient(135deg, #3d0e08 0%, #1a0405 50%, #200a0a 100%);
    border:1px solid rgba(230,57,70,.3);
    box-shadow: 0 0 80px rgba(230,57,70,.2);
}
.cta-dots {
    position:absolute; inset:0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='2' cy='2' r='1.5' fill='rgba(255,255,255,0.04)'/%3E%3C/svg%3E");
}

/* ── Hover Lift ── */
.hover-lift { transition:transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .25s ease; }
.hover-lift:hover { transform:translateY(-6px); box-shadow:0 20px 40px rgba(0,0,0,.5) !important; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 750, once: true, offset: 40 });
    });
</script>
@endpush
