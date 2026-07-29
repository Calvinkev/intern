@extends('layouts.app')

@section('title', 'Delivery Dashboard - CodeBase Food Ordering')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Delivery Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Available Deliveries</h5>
                    <h2 class="display-4">{{ $availableDeliveries->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Completed Today</h5>
                    <h2 class="display-4">{{ $completedDeliveries }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <h2 class="display-4">${{ number_format($totalEarnings, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Available Deliveries -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Available Deliveries</h5>
                    
                    @if($availableDeliveries->count() > 0)
                        <div class="list-group">
                            @foreach($availableDeliveries as $delivery)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Order #{{ $delivery->order->order_number }}</h6>
                                        <p class="text-muted small mb-1">{{ $delivery->order->restaurant->name }}</p>
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-geo-alt"></i> {{ $delivery->pickup_address }}
                                        </p>
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-flag"></i> {{ $delivery->delivery_address }}
                                        </p>
                                        <p class="fw-bold mb-0">${{ number_format($delivery->delivery_fee, 2) }}</p>
                                    </div>
                                    <form action="{{ route('delivery.accept', $delivery->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Accept</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No deliveries available at the moment.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Deliveries -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">My Deliveries</h5>
                    
                    @if($myDeliveries->count() > 0)
                        <div class="list-group">
                            @foreach($myDeliveries as $delivery)
                            <a href="{{ route('delivery.show', $delivery->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Order #{{ $delivery->order->order_number }}</h6>
                                        <p class="text-muted small mb-1">{{ $delivery->order->restaurant->name }}</p>
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-flag"></i> {{ $delivery->delivery_address }}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'picked_up' ? 'warning' : 'primary') }} mb-2">
                                            {{ ucfirst($delivery->status) }}
                                        </span>
                                        <p class="fw-bold mb-0">${{ number_format($delivery->delivery_fee, 2) }}</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">You haven't accepted any deliveries yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
