@extends('layouts.app')

@section('title', 'Edit Menu Item - Restaurant Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Menu Item</h1>
        <a href="{{ route('restaurant.admin.foods') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Menu
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('restaurant.admin.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Food Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $food->name }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category *</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $food->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_category" class="form-label">Or Add New Category</label>
                                    <input type="text" class="form-control" id="new_category" name="new_category" placeholder="e.g. Desserts">
                                    <small class="text-muted">Fill this if category is not in list</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $food->description }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Food Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Max size: 2MB. Leave empty to keep current image.</small>
                            @if($food->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $food->image) }}" class="rounded" style="max-height: 100px;" alt="Current image">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Pricing & Availability</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price * ($)</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="{{ $food->price }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_price" class="form-label">Discount Price ($) (Optional)</label>
                                    <input type="number" class="form-control" id="discount_price" name="discount_price" step="0.01" min="0" value="{{ $food->discount_price }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preparation_time" class="form-label">Preparation Time (minutes) *</label>
                                    <input type="number" class="form-control" id="preparation_time" name="preparation_time" min="1" value="{{ $food->preparation_time }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="calories" class="form-label">Calories (Optional)</label>
                                    <input type="number" class="form-control" id="calories" name="calories" min="0" value="{{ $food->calories }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" {{ $food->is_available ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">
                                        Available for ordering
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label for="stock_quantity" class="form-label">Stock Quantity (Optional)</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" value="{{ $food->stock_quantity }}" placeholder="e.g. 50">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" {{ $food->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured item (will appear on homepage)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Additional Details</h5>
                        
                        <div class="mb-3">
                            <label for="ingredients" class="form-label">Ingredients (comma-separated)</label>
                            <input type="text" class="form-control" id="ingredients" name="ingredients[]" value="{{ is_array($food->ingredients) ? implode(', ', $food->ingredients) : $food->ingredients }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="allergens" class="form-label">Allergens (comma-separated)</label>
                            <input type="text" class="form-control" id="allergens" name="allergens[]" value="{{ is_array($food->allergens) ? implode(', ', $food->allergens) : $food->allergens }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-check-circle"></i> Update Menu Item
                </button>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Item Statistics</h5>
                    <p><strong>Total Orders:</strong> {{ $food->order_count }}</p>
                    <p><strong>Rating:</strong> {{ number_format($food->rating, 1) }} / 5</p>
                    <p><strong>Reviews:</strong> {{ $food->total_reviews }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
