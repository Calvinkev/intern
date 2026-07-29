<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #fdf5f1;">
            Welcome back, {{ Auth::user()->name }} 👋
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Quick Actions -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <a href="{{ route('restaurants.index') }}" class="text-decoration-none">
                        <div class="card p-4 hover-lift text-center h-100" style="border-left: 4px solid #ff6b2b !important;">
                            <i class="bi bi-shop mb-3" style="font-size: 2.5rem; color: #ff6b2b;"></i>
                            <h5 class="fw-bold">Browse Restaurants</h5>
                            <p class="small" style="color: #c0aca3;">Discover amazing food near you</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('orders.index') }}" class="text-decoration-none">
                        <div class="card p-4 hover-lift text-center h-100" style="border-left: 4px solid #e63946 !important;">
                            <i class="bi bi-receipt mb-3" style="font-size: 2.5rem; color: #e63946;"></i>
                            <h5 class="fw-bold">My Orders</h5>
                            <p class="small" style="color: #c0aca3;">Track & view your order history</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('cart.index') }}" class="text-decoration-none">
                        <div class="card p-4 hover-lift text-center h-100" style="border-left: 4px solid #ffd166 !important;">
                            <i class="bi bi-cart mb-3" style="font-size: 2.5rem; color: #ffd166;"></i>
                            <h5 class="fw-bold">My Cart</h5>
                            <p class="small" style="color: #c0aca3;">Review items before checkout</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Call to action -->
            <div class="card p-5 text-center" style="background: linear-gradient(135deg, #1a0e0c 0%, #241c19 100%) !important; border: 1px solid #3b2f2b !important;">
                <i class="bi bi-rocket-takeoff mb-3" style="font-size: 3rem; color: #ff6b2b;"></i>
                <h3 class="fw-bold mb-2">Ready to order?</h3>
                <p style="color: #c0aca3;" class="mb-4">Browse our curated restaurants and find your next favourite meal.</p>
                <a href="{{ route('restaurants.index') }}" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                    <i class="bi bi-shop me-2"></i> Explore Restaurants
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
