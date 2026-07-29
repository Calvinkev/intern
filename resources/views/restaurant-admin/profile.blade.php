@extends('layouts.app')

@section('title', 'Restaurant Profile - Restaurant Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Restaurant Profile</h1>
        <a href="{{ route('restaurant.admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('restaurant.admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Restaurant Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $restaurant->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $restaurant->description }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ $restaurant->address }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $restaurant->phone }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $restaurant->email }}">
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Images</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <small class="text-muted">Max size: 2MB</small>
                                    @if($restaurant->logo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $restaurant->logo) }}" class="rounded" style="max-height: 80px;" alt="Current logo">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cover_image" class="form-label">Cover Image</label>
                                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                                    <small class="text-muted">Max size: 2MB</small>
                                    @if($restaurant->cover_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $restaurant->cover_image) }}" class="rounded" style="max-height: 80px;" alt="Current cover">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Operating Hours & Delivery</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="opening_time" class="form-label">Opening Time *</label>
                                    <input type="time" class="form-control" id="opening_time" name="opening_time" value="{{ $restaurant->opening_time }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="closing_time" class="form-label">Closing Time *</label>
                                    <input type="time" class="form-control" id="closing_time" name="closing_time" value="{{ $restaurant->closing_time }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="delivery_fee" class="form-label">Delivery Fee * ($)</label>
                                    <input type="number" class="form-control" id="delivery_fee" name="delivery_fee" step="0.01" min="0" value="{{ $restaurant->delivery_fee }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="min_order_amount" class="form-label">Min Order Amount * ($)</label>
                                    <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" step="0.01" min="0" value="{{ $restaurant->min_order_amount }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estimated_delivery_time" class="form-label">Est. Delivery Time * (min)</label>
                                    <input type="number" class="form-control" id="estimated_delivery_time" name="estimated_delivery_time" min="1" value="{{ $restaurant->estimated_delivery_time }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-check-circle"></i> Update Profile
                </button>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Current Status</h5>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $restaurant->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($restaurant->status) }}
                        </span>
                    </p>
                    <p><strong>Rating:</strong> {{ number_format($restaurant->rating, 1) }} / 5</p>
                    <p><strong>Total Reviews:</strong> {{ $restaurant->total_reviews }}</p>
                    <p><strong>Total Orders:</strong> {{ $restaurant->orders()->count() }}</p>
                    <p><strong>Menu Items:</strong> {{ $restaurant->foods()->count() }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Tips</h5>
                    <ul class="small text-muted">
                        <li>Keep your operating hours accurate</li>
                        <li>Set competitive delivery fees</li>
                        <li>Use high-quality images</li>
                        <li>Write a compelling description</li>
                        <li>Keep your menu updated</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
