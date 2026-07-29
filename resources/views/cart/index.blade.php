@extends('layouts.app')

@section('title', 'Shopping Cart - CodeBase Food Ordering')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>

    @if($cart && $cart->items->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        @foreach($cart->items as $item)
                            <div class="row align-items-center border-bottom py-3">
                                <div class="col-md-2">
                                    @if($item->food->image)
                                        <img src="{{ asset('storage/' . $item->food->image) }}" class="img-fluid rounded" alt="{{ $item->food->name }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                            <i class="bi bi-egg-fried text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item->food->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $item->food->restaurant->name }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-0">${{ number_format($item->unit_price, 2) }}</p>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" class="form-control" style="width: 60px;">
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <p class="fw-bold mb-1">${{ number_format($item->subtotal, 2) }}</p>
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
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Summary</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($cart->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span>
                            <span>${{ number_format($cart->delivery_fee, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%)</span>
                            <span>${{ number_format($cart->tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5">${{ number_format($cart->total, 2) }}</span>
                        </div>

                        <a href="{{ route('orders.checkout') }}" class="btn btn-primary w-100 btn-lg">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-primary w-100 mt-2">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>

                @if($cart->restaurant)
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="card-title">Restaurant Info</h6>
                            <p class="mb-1"><strong>{{ $cart->restaurant->name }}</strong></p>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-clock"></i> {{ $cart->restaurant->estimated_delivery_time }} min delivery
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Add some delicious items to get started!</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-shop"></i> Browse Restaurants
            </a>
        </div>
    @endif
</div>
@endsection
