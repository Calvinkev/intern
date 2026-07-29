@extends('layouts.app')

@section('title', 'Add Menu Item - Restaurant Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add Menu Item</h1>
        <a href="{{ route('restaurant.admin.foods') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Menu
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('restaurant.admin.foods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Food Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Food Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Max size: 2MB</small>
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
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_price" class="form-label">Discount Price ($) (Optional)</label>
                                    <input type="number" class="form-control" id="discount_price" name="discount_price" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preparation_time" class="form-label">Preparation Time (minutes) *</label>
                                    <input type="number" class="form-control" id="preparation_time" name="preparation_time" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="calories" class="form-label">Calories (Optional)</label>
                                    <input type="number" class="form-control" id="calories" name="calories" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available" checked>
                            <label class="form-check-label" for="is_available">
                                Available for ordering
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
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
                            <input type="text" class="form-control" id="ingredients" name="ingredients[]" placeholder="e.g., Tomato, Cheese, Beef">
                        </div>
                        
                        <div class="mb-3">
                            <label for="allergens" class="form-label">Allergens (comma-separated)</label>
                            <input type="text" class="form-control" id="allergens" name="allergens[]" placeholder="e.g., Gluten, Dairy, Nuts">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-plus-circle"></i> Add Menu Item
                </button>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Tips</h5>
                    <ul class="small text-muted">
                        <li>Use high-quality images to attract customers</li>
                        <li>Set competitive prices based on your costs</li>
                        <li>Accurate preparation times help manage expectations</li>
                        <li>Mark popular items as featured</li>
                        <li>List all allergens for customer safety</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
