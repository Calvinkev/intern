@extends('layouts.app')

@section('title', 'Order Details - System Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Order Details</h1>
        <a href="{{ route('admin.orders') }}" class="btn btn-outline_primary">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">Order #{{ $order->order_number }}</h5>
                            <p class="text-muted small mb-0">{{ $order->restaurant->name }}</p>
                        </div>
                        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }} fs-6">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Customer:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Customer Email:</strong> {{ $order->user->email }}</p>
                            <p class="mb-1"><strong>Customer Phone:</strong> {{ $order->delivery_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                            <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y - g:i A') }}</p>
                            @if($order->delivered_at)
                                <p class="mb-1"><strong>Delivered At:</strong> {{ $order->delivered_at->format('M d, Y - g:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($order->delivery_notes)
                        <div class="mt-2">
                            <strong>Delivery Notes:</strong>
                            <p class="text-muted">{{ $order->delivery_notes }}</p>
                        </div>
                    @endif

                    @if($order->cancellation_reason)
                        <div class="alert alert-danger mt-2">
                            <strong>Cancelled:</strong> {{ $order->cancellation_reason }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Items</h5>
                    
                    <ul class="list-group">
                        @foreach($order->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $item->food_name }}</h6>
                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                            <span class="fw-bold">${{ number_format($item->subtotal, 2) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Payment Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Payment Information</h5>
                    
                    <div class="mb-3">
                        <strong>Payment Method:</strong>
                        <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Status:</strong>
                        <p class="mb-0">
                            <span class="badge bg-{{ $order->payment->status == 'completed' ? 'success' : ($order->payment->status == 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        </p>
                    </div>

                    @if($order->payment->transaction_id)
                        <div class="mb-3">
                            <strong>Transaction ID:</strong>
                            <p class="mb-0 text-muted small">{{ $order->payment->transaction_id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee</span>
                        <span>${{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%)</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount</span>
                            <span>-${{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            @if($order->delivery)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Delivery Information</h5>
                        
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <p class="mb-0">
                                <span class="badge bg-{{ $order->delivery->status == 'delivered' ? 'success' : 'primary' }}">
                                    {{ ucfirst($order->delivery->status) }}
                                </span>
                            </p>
                        </div>

                        @if($order->delivery->deliveryPersonnel)
                            <div class="mb-3">
                                <strong>Delivery Personnel:</strong>
                                <p class="mb-0">{{ $order->delivery->deliveryPersonnel->name }}</p>
                                <p class="mb-0 text-muted small">{{ $order->delivery->deliveryPersonnel->phone }}</p>
                            </div>
                        @endif

                        @if($order->delivery->distance)
                            <div class="mb-3">
                                <strong>Distance:</strong>
                                <p class="mb-0">{{ number_format($order->delivery->distance, 2) }} km</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
