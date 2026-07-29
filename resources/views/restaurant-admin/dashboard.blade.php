@extends('layouts.app')

@section('title', 'Restaurant Dashboard - CodeBase Food Ordering')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Restaurant Dashboard</h1>
        <a href="{{ route('restaurant.admin.profile') }}" class="btn btn-outline-primary">
            <i class="bi bi-gear"></i> Settings
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Orders</h5>
                    <h2 class="display-4">{{ $pendingOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">In Progress</h5>
                    <h2 class="display-4">{{ $confirmedOrders + $preparingOrders + $readyOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h2 class="display-4">{{ $completedOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2 class="display-4">${{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                        <a href="{{ route('restaurant.admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><a href="#">{{ $order->order_number }}</a></td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No orders yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Popular Foods -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Popular Items</h5>
                        <a href="{{ route('restaurant.admin.foods') }}" class="btn btn-sm btn-outline-primary">Manage</a>
                    </div>
                    
                    @if($popularFoods->count() > 0)
                        <div class="list-group">
                            @foreach($popularFoods as $food)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $food->name }}</h6>
                                    <small class="text-muted">{{ $food->order_count }} orders</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">${{ number_format($food->price, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No menu items yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('restaurant.admin.orders') }}" class="card text-decoration-none text-dark">
                <div class="card-body text-center">
                    <i class="bi bi-receipt" style="font-size: 3rem;"></i>
                    <h5 class="mt-2">Manage Orders</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('restaurant.admin.foods.create') }}" class="card text-decoration-none text-dark">
                <div class="card-body text-center">
                    <i class="bi bi-plus-circle" style="font-size: 3rem;"></i>
                    <h5 class="mt-2">Add Menu Item</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('restaurant.admin.profile') }}" class="card text-decoration-none text-dark">
                <div class="card-body text-center">
                    <i class="bi bi-shop" style="font-size: 3rem;"></i>
                    <h5 class="mt-2">Restaurant Profile</h5>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
