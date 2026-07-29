@extends('layouts.app')

@section('title', 'Manage Orders - Restaurant Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Orders</h1>
        <a href="{{ route('restaurant.admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Order Status Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('restaurant.admin.orders') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                <a href="{{ route('restaurant.admin.orders', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
                <a href="{{ route('restaurant.admin.orders', ['status' => 'confirmed']) }}" class="btn {{ request('status') == 'confirmed' ? 'btn-primary' : 'btn-outline-primary' }}">Confirmed</a>
                <a href="{{ route('restaurant.admin.orders', ['status' => 'preparing']) }}" class="btn {{ request('status') == 'preparing' ? 'btn-primary' : 'btn-outline-primary' }}">Preparing</a>
                <a href="{{ route('restaurant.admin.orders', ['status' => 'ready']) }}" class="btn {{ request('status') == 'ready' ? 'btn-primary' : 'btn-outline-primary' }}">Ready</a>
                <a href="{{ route('restaurant.admin.orders', ['status' => 'delivered']) }}" class="btn {{ request('status') == 'delivered' ? 'btn-primary' : 'btn-outline-primary' }}">Delivered</a>
            </div>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="card-title mb-1">Order #{{ $order->order_number }}</h5>
                                            <p class="text-muted small mb-0">{{ $order->user->name }} - {{ $order->user->phone }}</p>
                                        </div>
                                        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }} fs-6">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar"></i> {{ $order->created_at->format('M d, Y - g:i A') }}
                                    </p>
                                    <p class="mb-2"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                                    <p class="mb-2"><strong>Delivery Phone:</strong> {{ $order->delivery_phone }}</p>
                                    @if($order->delivery_notes)
                                        <p class="mb-2"><strong>Notes:</strong> {{ $order->delivery_notes }}</p>
                                    @endif
                                    
                                    <h6 class="mt-3 mb-2">Order Items:</h6>
                                    <ul class="list-unstyled small">
                                        @foreach($order->items as $item)
                                            <li>{{ $item->quantity }}x {{ $item->food_name }} - Shs {{ number_format($item->subtotal, 0) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Order Summary</h6>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Subtotal</span>
                                                <span>Shs {{ number_format($order->subtotal, 0) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Delivery Fee</span>
                                                <span>Shs {{ number_format($order->delivery_fee, 0) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Tax</span>
                                                <span>Shs {{ number_format($order->tax, 0) }}</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Total</span>
                                                <span class="fw-bold">Shs {{ number_format($order->total, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($order->status == 'pending' || $order->status == 'confirmed' || $order->status == 'preparing')
                                        <form action="{{ route('restaurant.admin.orders.update-status', $order->id) }}" method="POST" class="mt-3">
                                            @csrf
                                            <div class="mb-2">
                                                <label class="form-label small">Update Status:</label>
                                                <select name="status" class="form-select form-select-sm">
                                                    @if($order->status == 'pending')
                                                        <option value="confirmed">Confirm Order</option>
                                                        <option value="rejected">Reject Order</option>
                                                    @elseif($order->status == 'confirmed')
                                                        <option value="preparing">Start Preparing</option>
                                                    @elseif($order->status == 'preparing')
                                                        <option value="ready">Mark as Ready</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-receipt text-muted" style="font-size: 5rem;"></i>
            <h3 class="mt-3">No orders found</h3>
            <p class="text-muted">Orders will appear here when customers place them.</p>
        </div>
    @endif
</div>
@endsection
