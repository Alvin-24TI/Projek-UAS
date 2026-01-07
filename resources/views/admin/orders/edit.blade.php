@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Order {{ $order->invoice_number }}</h1>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Status Update -->
                    <div class="mb-4">
                        <label for="status" class="form-label">Order Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Customer Info (Read-only) -->
                    <div class="mb-4">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" value="{{ $order->user->name }} ({{ $order->user->email }})" disabled>
                        <small class="text-muted">Customer cannot be changed for existing orders</small>
                    </div>

                    <!-- Current Items -->
                    <h6 class="mb-3">Current Items</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-light">
                                    <td colspan="3"><strong>Total</strong></td>
                                    <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mb-4">
                        <strong>Note:</strong> You can update the order status. To modify items, delete and recreate the order.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Order</button>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Invoice:</strong><br>
                    {{ $order->invoice_number }}
                </p>
                <p class="mb-2">
                    <strong>Total Items:</strong><br>
                    {{ $order->orderItems->count() }}
                </p>
                <p class="mb-2">
                    <strong>Total Price:</strong><br>
                    <span class="text-success" style="font-size: 1.3em;">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </p>
                <hr>
                <p class="mb-0 text-muted small">
                    Created: {{ $order->created_at->format('d M Y H:i') }}<br>
                    Last updated: {{ $order->updated_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
