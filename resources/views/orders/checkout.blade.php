@extends('layouts.app')

@section('title', 'Checkout - CODEBASE FOODS')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Checkout</h1>

    @if($cart && !$cart->isEmpty())
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('orders.place') }}" method="POST">
                    @csrf
                    
                    <!-- Delivery Information -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Delivery Information</h5>
                            
                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Delivery Address *</label>
                                <input type="text" class="form-control" id="delivery_address" name="delivery_address" required value="{{ auth()->user()->address ?? '' }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="delivery_phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="delivery_phone" name="delivery_phone" required value="{{ auth()->user()->phone ?? '' }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="delivery_notes" class="form-label">Delivery Notes (Optional)</label>
                                <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="3" placeholder="Apartment number, building name, landmarks, etc."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Payment Method</h5>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cash_on_delivery" checked>
                                <label class="form-check-label" for="cod">
                                    <i class="bi bi-cash"></i> Cash on Delivery
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="mtn" value="mtn_mobile_money">
                                <label class="form-check-label" for="mtn">
                                    <i class="bi bi-phone"></i> MTN Mobile Money
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="airtel" value="airtel_money">
                                <label class="form-check-label" for="airtel">
                                    <i class="bi bi-phone"></i> Airtel Money
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                                <label class="form-check-label" for="stripe">
                                    <i class="bi bi-credit-card"></i> Credit/Debit Card (Stripe)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Order Items</h5>
                            
                            @foreach($cart->items as $item)
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <div>
                                        <h6 class="mb-0">{{ $item->food->name }}</h6>
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    </div>
                                    <span class="fw-bold">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-check-circle"></i> Place Order
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

                        @if($cart->restaurant)
                            <div class="alert alert-info">
                                <h6 class="alert-heading mb-2">Restaurant Info</h6>
                                <p class="mb-1"><strong>{{ $cart->restaurant->name }}</strong></p>
                                <p class="small mb-0">
                                    <i class="bi bi-clock"></i> {{ $cart->restaurant->estimated_delivery_time }} min delivery
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
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
