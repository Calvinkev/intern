@extends('layouts.app')

@section('title', 'Manage Restaurants - System Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Restaurants</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($restaurants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Owner</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($restaurants as $restaurant)
                            <tr>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ $restaurant->user->name }}</td>
                                <td>{{ $restaurant->email }}</td>
                                <td>{{ $restaurant->phone }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill"></i> {{ number_format($restaurant->rating, 1) }}
                                    </span>
                                </td>
                                <td>
                                    @if($restaurant->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.restaurants.toggle-status', $restaurant->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $restaurant->status == 'active' ? 'btn-danger' : 'btn-success' }}">
                                            {{ $restaurant->status == 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $restaurants->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-shop text-muted" style="font-size: 5rem;"></i>
                    <h3 class="mt-3">No restaurants found</h3>
                    <p class="text-muted">Restaurants will appear here when they are registered.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
