@extends('layouts.app')

@section('title', 'Checkout - CODEBASE FOODS')

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
        <h1 class="fw-bolder display-6">
            <i class="bi bi-bag-check me-2" style="color:#ff6b2b;"></i> Secure Checkout
        </h1>
        <p style="color:#c0aca3;">You're almost there! Review your order and complete payment.</p>
    </div>

    @if($cart && !$cart->isEmpty())
    <div class="row g-4">

        {{-- LEFT: Checkout Form --}}
        <div class="col-lg-8">
            <form action="{{ route('orders.place') }}" method="POST" id="checkout-form">
                @csrf

                {{-- Step 1: Delivery Info --}}
                <div class="card mb-4 p-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4 gap-3">
                            <div class="step-circle" style="background: var(--gradient); width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#fff; flex-shrink:0;">1</div>
                            <h5 class="card-title mb-0 fw-bold">Delivery Information</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="delivery_address" class="form-label fw-medium" style="color:#c0aca3;">
                                    <i class="bi bi-geo-alt me-1" style="color:#ff6b2b;"></i> Delivery Address <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control py-3" id="delivery_address" name="delivery_address"
                                    required value="{{ auth()->user()->address ?? '' }}"
                                    placeholder="Enter your full delivery address">
                                @error('delivery_address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="delivery_phone" class="form-label fw-medium" style="color:#c0aca3;">
                                    <i class="bi bi-telephone me-1" style="color:#ff6b2b;"></i> Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control py-3" id="delivery_phone" name="delivery_phone"
                                    required value="{{ auth()->user()->phone ?? '' }}"
                                    placeholder="+256 7XX XXX XXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium" style="color:#c0aca3;">
                                    <i class="bi bi-clock me-1" style="color:#ff6b2b;"></i> Estimated Delivery
                                </label>
                                <div class="form-control py-3 d-flex align-items-center gap-2" style="cursor:default;">
                                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                                    <span>{{ $cart->restaurant ? $cart->restaurant->estimated_delivery_time . ' minutes' : '30-45 minutes' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="delivery_notes" class="form-label fw-medium" style="color:#c0aca3;">
                                    <i class="bi bi-chat-left-text me-1" style="color:#ff6b2b;"></i> Special Instructions (Optional)
                                </label>
                                <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="3"
                                    placeholder="Apartment number, gate code, or any special instructions for the driver..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Payment Method --}}
                <div class="card mb-4 p-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4 gap-3">
                            <div class="step-circle" style="background: var(--gradient); width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#fff; flex-shrink:0;">2</div>
                            <h5 class="card-title mb-0 fw-bold">Payment Method</h5>
                        </div>
                        <div class="row g-3">
                            @php
                                $methods = [
                                    ['value'=>'cash_on_delivery', 'icon'=>'bi-cash-coin', 'label'=>'Cash on Delivery', 'desc'=>'Pay when your food arrives', 'color'=>'#4ade80'],
                                    ['value'=>'mtn_mobile_money', 'icon'=>'bi-phone-fill', 'label'=>'MTN Mobile Money', 'desc'=>'Pay via MTN MoMo', 'color'=>'#facc15'],
                                    ['value'=>'airtel_money', 'icon'=>'bi-phone', 'label'=>'Airtel Money', 'desc'=>'Pay via Airtel Money', 'color'=>'#f87171'],
                                    ['value'=>'stripe', 'icon'=>'bi-credit-card-2-front', 'label'=>'Credit / Debit Card', 'desc'=>'Visa, Mastercard, etc.', 'color'=>'#60a5fa'],
                                ];
                            @endphp
                            @foreach($methods as $i => $method)
                            <div class="col-md-6">
                                <label class="payment-option d-block p-3 rounded-3 cursor-pointer" for="{{ $method['value'] }}"
                                    style="border: 2px solid var(--border-color); transition: all 0.2s ease; cursor:pointer;">
                                    <input class="d-none" type="radio" name="payment_method" id="{{ $method['value'] }}"
                                        value="{{ $method['value'] }}" {{ $i === 0 ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi {{ $method['icon'] }} fs-3" style="color:{{ $method['color'] }};"></i>
                                        <div>
                                            <div class="fw-bold">{{ $method['label'] }}</div>
                                            <div class="small" style="color:#c0aca3;">{{ $method['desc'] }}</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Step 3: Review Items --}}
                <div class="card mb-4 p-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4 gap-3">
                            <div class="step-circle" style="background: var(--gradient); width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#fff; flex-shrink:0;">3</div>
                            <h5 class="card-title mb-0 fw-bold">Order Items</h5>
                        </div>
                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between align-items-center py-3" style="border-bottom:1px solid var(--border-color);">
                                <div class="d-flex align-items-center gap-3">
                                    @if($item->food->image)
                                        <img src="{{ asset('storage/' . $item->food->image) }}" class="rounded-3 object-fit-cover" style="width:52px;height:52px;" alt="{{ $item->food->name }}">
                                    @else
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:#2e2420;">
                                            <i class="bi bi-egg-fried" style="color:#ff6b2b;font-size:1.4rem;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $item->food->name }}</div>
                                        <div class="small" style="color:#c0aca3;">Qty: {{ $item->quantity }}</div>
                                    </div>
                                </div>
                                <span class="fw-bold" style="color:#ff6b2b;">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Place Order CTA (mobile) --}}
                <div class="d-lg-none">
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow-lg fw-bold">
                        <i class="bi bi-check-circle me-2"></i> Place Order
                    </button>
                </div>

            </form>
        </div>

        {{-- RIGHT: Order Summary --}}
        <div class="col-lg-4">
            <div class="card mb-4 p-2" style="position:sticky;top:1.5rem;">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">
                        <i class="bi bi-receipt me-2" style="color:#ff6b2b;"></i> Order Summary
                    </h5>

                    <div class="d-flex justify-content-between mb-2" style="color:#c0aca3;">
                        <span>Subtotal</span>
                        <span>${{ number_format($cart->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="color:#c0aca3;">
                        <span>Delivery Fee</span>
                        <span>${{ number_format($cart->delivery_fee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3" style="color:#c0aca3;">
                        <span>Tax (10%)</span>
                        <span>${{ number_format($cart->tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bolder fs-5">Total</span>
                        <span class="fw-bolder fs-4" style="color:#ff6b2b;">${{ number_format($cart->total, 2) }}</span>
                    </div>

                    @if($cart->restaurant)
                        <div class="rounded-3 p-3 mb-4" style="background:#2e2420; border:1px solid #3b2f2b;">
                            <div class="fw-bold mb-1">{{ $cart->restaurant->name }}</div>
                            <div class="small" style="color:#c0aca3;">
                                <i class="bi bi-clock text-warning me-1"></i>
                                {{ $cart->restaurant->estimated_delivery_time }} min estimated delivery
                            </div>
                        </div>
                    @endif

                    <div class="d-none d-lg-block">
                        <button type="submit" form="checkout-form" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow-lg fw-bold">
                            <i class="bi bi-check-circle me-2"></i> Place Order
                        </button>
                        <p class="text-center small mt-3" style="color:#c0aca3;">
                            <i class="bi bi-shield-lock me-1 text-success"></i> Secure & encrypted checkout
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="text-center py-5">
        <i class="bi bi-cart-x" style="font-size:5rem; color:#3b2f2b;"></i>
        <h3 class="mt-3 fw-bold">Your cart is empty</h3>
        <p class="mb-4" style="color:#c0aca3;">Add some delicious items to get started!</p>
        <a href="{{ route('restaurants.index') }}" class="btn btn-primary btn-lg rounded-pill px-5">
            <i class="bi bi-shop me-2"></i> Browse Restaurants
        </a>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .payment-option:has(input:checked) {
        border-color: #ff6b2b !important;
        background-color: rgba(255,107,43,0.07);
    }
    .payment-option:hover {
        border-color: #ff6b2b !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }
</style>
@endpush
