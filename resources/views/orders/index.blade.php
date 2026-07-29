@extends('layouts.app')

@section('title', 'My Orders - CODEBASE FOODS')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">My Orders</h1>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="card-title mb-1">Order #{{ $order->order_number }}</h5>
                                            <p class="text-muted small mb-0">{{ $order->restaurant->name }}</p>
                                        </div>
                                        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar"></i> {{ $order->created_at->format('M d, Y - g:i A') }}
                                    </p>
                                    <p class="fw-bold mb-0">${{ number_format($order->total, 2) }}</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-outline-primary">
                                        View Details
                                    </a>
                                    @if($order->canBeCancelled())
                                        <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" class="d-inline mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                Cancel Order
                                            </button>
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
            <h3 class="mt-3">No orders yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders. Start ordering delicious food!</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-shop"></i> Browse Restaurants
            </a>
        </div>
    @endif
</div>
@endsection
