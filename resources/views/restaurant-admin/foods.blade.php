@extends('layouts.app')

@section('title', 'Manage Menu - Restaurant Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Menu</h1>
        <a href="{{ route('restaurant.admin.foods.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Item
        </a>
    </div>

    @if($foods->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Orders</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foods as $food)
                    <tr>
                        <td>
                            @if($food->image)
                                <img src="{{ asset('storage/' . $food->image) }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $food->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-egg-fried text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->category->name }}</td>
                        <td>
                            @if($food->hasDiscount())
                                <div>
                                    <span class="text-decoration-line-through text-muted small">${{ number_format($food->price, 2) }}</span>
                                    <span class="text-danger fw-bold">${{ number_format($food->getDiscountedPrice(), 2) }}</span>
                                </div>
                            @else
                                ${{ number_format($food->price, 2) }}
                            @endif
                        </td>
                        <td>
                            @if($food->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Unavailable</span>
                            @endif
                            @if($food->is_featured)
                                <span class="badge bg-warning text-dark">Featured</span>
                            @endif
                        </td>
                        <td>{{ $food->order_count }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('restaurant.admin.foods.edit', $food->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('restaurant.admin.foods.delete', $food->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $foods->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-egg-fried text-muted" style="font-size: 5rem;"></i>
            <h3 class="mt-3">No menu items yet</h3>
            <p class="text-muted mb-4">Start adding delicious items to your menu!</p>
            <a href="{{ route('restaurant.admin.foods.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Add Your First Item
            </a>
        </div>
    @endif
</div>
@endsection
