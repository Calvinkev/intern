@extends('layouts.app')
@section('title', 'Add Restaurant - Admin Panel')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bolder display-6 mb-1">
                <i class="bi bi-shop me-2" style="color:#ff6b2b;"></i> Add New Restaurant
            </h1>
            <p style="color:#c0aca3;">Create a restaurant owner account and their restaurant profile in one step.</p>
        </div>
        <a href="{{ route('admin.restaurants') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4 rounded-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.restaurants.store') }}" method="POST">
        @csrf
        <div class="row g-4">

            {{-- LEFT: Owner Account --}}
            <div class="col-lg-6">
                <div class="card p-2 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div style="width:40px;height:40px;border-radius:50%;background:rgba(255,107,43,.15);display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person-fill" style="color:#ff6b2b;"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Owner Account</h5>
                        </div>
                        <p class="small mb-4" style="color:#c0aca3;">These credentials will be used by the restaurant owner to log in.</p>

                        <div class="mb-3">
                            <label class="form-label fw-medium" style="color:#c0aca3;">Full Name *</label>
                            <input type="text" name="owner_name" class="form-control py-3" value="{{ old('owner_name') }}" placeholder="e.g. John Okello" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium" style="color:#c0aca3;">Login Email *</label>
                            <input type="email" name="owner_email" class="form-control py-3" value="{{ old('owner_email') }}" placeholder="owner@restaurant.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium" style="color:#c0aca3;">Password *</label>
                            <input type="password" name="owner_password" class="form-control py-3" placeholder="Min. 8 characters" required>
                            <div class="form-text" style="color:#c0aca3;">Share these credentials with the restaurant owner after creation.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Restaurant Details --}}
            <div class="col-lg-6">
                <div class="card p-2 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div style="width:40px;height:40px;border-radius:50%;background:rgba(230,57,70,.15);display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-shop" style="color:#e63946;"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Restaurant Details</h5>
                        </div>
                        <p class="small mb-4" style="color:#c0aca3;">This info will be shown publicly on the platform.</p>

                        <div class="mb-3">
                            <label class="form-label fw-medium" style="color:#c0aca3;">Restaurant Name *</label>
                            <input type="text" name="restaurant_name" class="form-control py-3" value="{{ old('restaurant_name') }}" placeholder="e.g. Kampala Bites" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium" style="color:#c0aca3;">Description</label>
                            <textarea name="restaurant_description" class="form-control" rows="3" placeholder="Short description of the restaurant...">{{ old('restaurant_description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium" style="color:#c0aca3;">Address *</label>
                            <input type="text" name="restaurant_address" class="form-control py-3" value="{{ old('restaurant_address') }}" placeholder="e.g. Plot 12 Kampala Road, Kampala" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium" style="color:#c0aca3;">Phone *</label>
                                <input type="text" name="restaurant_phone" class="form-control py-3" value="{{ old('restaurant_phone') }}" placeholder="+256 7XX XXX XXX" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium" style="color:#c0aca3;">Email *</label>
                                <input type="email" name="restaurant_email" class="form-control py-3" value="{{ old('restaurant_email') }}" placeholder="info@restaurant.com" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium" style="color:#c0aca3;">Delivery Fee (Shs)</label>
                                <input type="number" name="delivery_fee" class="form-control py-3" value="{{ old('delivery_fee', 3000) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium" style="color:#c0aca3;">Min Order (Shs)</label>
                                <input type="number" name="min_order_amount" class="form-control py-3" value="{{ old('min_order_amount', 10000) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium" style="color:#c0aca3;">Est. Time (min)</label>
                                <input type="number" name="estimated_delivery_time" class="form-control py-3" value="{{ old('estimated_delivery_time', 30) }}" min="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="col-12">
                <div class="card p-4" style="background:rgba(255,107,43,.05); border-color:rgba(255,107,43,.2) !important;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <i class="bi bi-info-circle me-2" style="color:#ff6b2b;"></i>
                            <span style="color:#c0aca3;">After creation, the owner can log in and manage their menu, orders, and profile from their dashboard.</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">
                            <i class="bi bi-plus-circle me-2"></i> Create Restaurant
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
