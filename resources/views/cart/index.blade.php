@extends('layouts.app')

@section('title', 'Shopping Cart - CODEBASE FOODS')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>

    @if($cart && $cart->items->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4" style="background-color: #241c19; border: 1px solid rgba(255,255,255,0.05); border-radius: 1rem;">
                    <div class="card-body">
                        @foreach($cart->items as $item)
                            <div class="row align-items-center py-3" style="border-bottom: 1px solid #3b2f2b;">
                                <div class="col-md-2">
                                    @if($item->food->image)
                                        <img src="{{ asset('storage/' . $item->food->image) }}" class="img-fluid rounded-4 shadow-sm" alt="{{ $item->food->name }}">
                                    @else
                                        <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="height: 80px; background-color: #1a1412;">
                                            <i class="bi bi-egg-fried" style="font-size: 2rem; color: #ff6b2b;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1 fw-bold text-white">{{ $item->food->name }}</h6>
                                    <p class="small mb-0" style="color: #c0aca3;">{{ $item->food->restaurant->name }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-0">Shs {{ number_format($item->unit_price, 0) }}</p>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex w-100">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" class="form-control text-center text-white" style="background-color: #1a1412; border: 1px solid #3b2f2b; max-width: 60px;">
                                            <button type="submit" class="btn" style="background-color: #2e2420; color: #fdf5f1; border: 1px solid #3b2f2b;">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <p class="fw-bold mb-1">Shs {{ number_format($item->subtotal, 0) }}</p>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i> Clear Cart
                    </button>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card" style="background-color: #241c19; border: 1px solid rgba(255,255,255,0.05); border-radius: 1rem;">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 fw-bold text-white">Order Summary</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Shs {{ number_format($cart->subtotal, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span>
                            <span>Shs {{ number_format($cart->delivery_fee, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%)</span>
                            <span>Shs {{ number_format($cart->tax, 0) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5">Shs {{ number_format($cart->total, 0) }}</span>
                        </div>

                        <a href="{{ route('orders.checkout') }}" class="btn btn-primary w-100 btn-lg shadow-lg" style="background: linear-gradient(135deg, #ff6b2b 0%, #e63946 100%); border: none;">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('restaurants.index') }}" class="btn w-100 mt-3 hover-lift" style="background-color: #1a1412; color: #ff6b2b; border: 1px solid rgba(255,255,255,0.1);">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>

                @if($cart->restaurant)
                    <div class="card mt-4" style="background-color: #241c19; border: 1px solid rgba(255,255,255,0.05); border-radius: 1rem;">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-white">Restaurant Info</h6>
                            <p class="mb-1"><strong>{{ $cart->restaurant->name }}</strong></p>
                            <p class="small mb-0" style="color: #c0aca3;">
                                <i class="bi bi-clock text-warning"></i> {{ $cart->restaurant->estimated_delivery_time }} min delivery
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #3b2f2b;"></i>
            <h3 class="mt-3 text-white fw-bold">Your cart is empty</h3>
            <p class="mb-4" style="color: #c0aca3;">Add some delicious items to get started!</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-lg shadow-lg" style="background: linear-gradient(135deg, #ff6b2b 0%, #e63946 100%); border: none; color: white;">
                <i class="bi bi-shop"></i> Browse Restaurants
            </a>
        </div>
    @endif
</div>
@endsection
