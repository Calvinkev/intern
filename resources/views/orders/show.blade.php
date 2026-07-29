@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - CodeBase Food Ordering')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Order Status</h5>
                        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }} fs-6">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <!-- Order Progress -->
                    @if($order->status != 'cancelled' && $order->status != 'rejected')
                        <div class="progress mb-3" style="height: 30px;">
                            @php
                                $steps = ['pending', 'confirmed', 'preparing', 'ready', 'picked_up', 'delivered'];
                                $currentStep = array_search($order->status, $steps);
                            @endphp
                            @foreach($steps as $index => $step)
                                @if($index <= $currentStep)
                                    <div class="progress-bar bg-success" style="width: {{ 100 / count($steps) }}%">
                                        {{ ucfirst($step) }}
                                    </div>
                                @else
                                    <div class="progress-bar bg-secondary" style="width: {{ 100 / count($steps) }}%">
                                        {{ ucfirst($step) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y - g:i A') }}</p>
                            <p class="mb-1"><strong>Estimated Delivery:</strong> {{ $order->estimated_delivery_time ? $order->estimated_delivery_time->format('M d, Y - g:i A') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                            <p class="mb-1"><strong>Delivery Phone:</strong> {{ $order->delivery_phone }}</p>
                        </div>
                    </div>

                    @if($order->delivery_notes)
                        <div class="mt-2">
                            <strong>Delivery Notes:</strong>
                            <p class="text-muted">{{ $order->delivery_notes }}</p>
                        </div>
                    @endif

                    @if($order->status == 'cancelled')
                        <div class="alert alert-danger mt-3">
                            <strong>Cancelled:</strong> {{ $order->cancellation_reason }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Items</h5>
                    
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                            <div>
                                <h6 class="mb-1">{{ $item->food_name }}</h6>
                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                            <span class="fw-bold">${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Restaurant Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Restaurant</h5>
                    <h6>{{ $order->restaurant->name }}</h6>
                    <p class="text-muted mb-1"><i class="bi bi-geo-alt"></i> {{ $order->restaurant->address }}</p>
                    <p class="text-muted mb-1"><i class="bi bi-telephone"></i> {{ $order->restaurant->phone }}</p>
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

                    @if($order->canBeCancelled())
                        <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="bi bi-x-circle"></i> Cancel Order
                            </button>
                        </form>
                    @endif

                    @if($order->isDelivered() && !$order->review)
                        <a href="#" class="btn btn-primary w-100 mt-2">
                            <i class="bi bi-star"></i> Leave a Review
                        </a>
                    @endif
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
