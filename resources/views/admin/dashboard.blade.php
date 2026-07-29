@extends('layouts.app')

@section('title', 'Admin Dashboard - CodeBase Food Ordering')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">System Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2 class="display-4">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Restaurants</h5>
                    <h2 class="display-4">{{ $totalRestaurants }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2 class="display-4">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2 class="display-4">${{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Restaurants</h5>
                    <h3 class="text-success">{{ $activeRestaurants }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending Orders</h5>
                    <h3 class="text-warning">{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Completed Orders</h5>
                    <h3 class="text-primary">{{ $completedOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Users by Role -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Users by Role</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Customers
                            <span class="badge bg-primary rounded-pill">{{ $usersByRole['customers'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Restaurant Admins
                            <span class="badge bg-success rounded-pill">{{ $usersByRole['restaurant_admins'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Delivery Personnel
                            <span class="badge bg-info rounded-pill">{{ $usersByRole['delivery_personnel'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            System Admins
                            <span class="badge bg-warning text-dark rounded-pill">{{ $usersByRole['system_admins'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Top Restaurants -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Top Restaurants</h5>
                        <a href="{{ route('admin.restaurants') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    
                    @if($topRestaurants->count() > 0)
                        <div class="list-group">
                            @foreach($topRestaurants as $restaurant)
                            <div class="list-group-item">
                                <h6 class="mb-1">{{ $restaurant->name }}</h6>
                                <small class="text-muted">{{ $restaurant->user->name }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill"></i> {{ number_format($restaurant->rating, 1) }}
                                    </span>
                                    <span class="badge bg-secondary">{{ $restaurant->total_reviews }} reviews</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No restaurants yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Popular Foods -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Popular Foods</h5>
                    
                    @if($popularFoods->count() > 0)
                        <div class="list-group">
                            @foreach($popularFoods as $food)
                            <div class="list-group-item">
                                <h6 class="mb-1">{{ $food->name }}</h6>
                                <small class="text-muted">{{ $food->restaurant->name }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-primary">{{ $food->order_count }} orders</span>
                                    <span class="badge bg-success">${{ number_format($food->price, 2) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No foods yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Restaurant</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order->order_number) }}">{{ $order->order_number }}</a></td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->restaurant->name }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
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
    </div>
</div>
@endsection
