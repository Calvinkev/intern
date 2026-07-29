@extends('layouts.app')

@section('title', 'Delivery Details - CODEBASE FOODS')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Delivery Details</h1>
        <a href="{{ route('delivery.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Order #{{ $delivery->order->order_number }}</h5>
                        <span class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'picked_up' ? 'warning' : 'primary') }} fs-6">
                            {{ ucfirst($delivery->status) }}
                        </span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Restaurant:</strong> {{ $delivery->order->restaurant->name }}</p>
                            <p class="mb-1"><strong>Customer:</strong> {{ $delivery->order->user->name }}</p>
                            <p class="mb-1"><strong>Customer Phone:</strong> {{ $delivery->order->delivery_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Pickup Address:</strong> {{ $delivery->pickup_address }}</p>
                            <p class="mb-1"><strong>Delivery Address:</strong> {{ $delivery->delivery_address }}</p>
                            <p class="mb-1"><strong>Delivery Fee:</strong> Shs {{ number_format($delivery->delivery_fee, 0) }}</p>
                        </div>
                    </div>

                    @if($delivery->order->delivery_notes)
                        <div class="mt-2">
                            <strong>Delivery Notes:</strong>
                            <p class="text-muted">{{ $delivery->order->delivery_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Items</h5>
                    
                    <ul class="list-group">
                        @foreach($delivery->order->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $item->food_name }}</h6>
                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                            <span class="fw-bold">Shs {{ number_format($item->subtotal, 0) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Delivery Actions -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Delivery Actions</h5>
                    
                    @if($delivery->status == 'accepted')
                        <form action="{{ route('delivery.update-status', $delivery->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="picked_up">
                            <button type="submit" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-box-seam"></i> Mark as Picked Up
                            </button>
                        </form>
                    @elseif($delivery->status == 'picked_up')
                        <form action="{{ route('delivery.update-status', $delivery->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-check-circle"></i> Mark as Delivered
                            </button>
                        </form>
                    @elseif($delivery->status == 'delivered')
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Delivery completed successfully!
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
                        <span>Shs {{ number_format($delivery->order->subtotal, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee</span>
                        <span>Shs {{ number_format($delivery->order->delivery_fee, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax</span>
                        <span>Shs {{ number_format($delivery->order->tax, 0) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5">Shs {{ number_format($delivery->order->total, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Contact Information</h5>
                    <p class="mb-1"><strong>Restaurant:</strong></p>
                    <p class="text-muted">{{ $delivery->order->restaurant->name }}</p>
                    <p class="text-muted">{{ $delivery->order->restaurant->phone }}</p>
                    <hr>
                    <p class="mb-1"><strong>Customer:</strong></p>
                    <p class="text-muted">{{ $delivery->order->user->name }}</p>
                    <p class="text-muted">{{ $delivery->order->delivery_phone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
