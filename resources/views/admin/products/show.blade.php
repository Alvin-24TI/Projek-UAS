@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $product->name }}</h1>
    <div class="gap-2 d-flex">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</button>
        </form>
        @endif
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit: cover; height: 300px;">
            @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                <span class="text-muted">No image</span>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Product Information</h5>

                <dl class="row mb-0">
                    <dt class="col-sm-3">Category:</dt>
                    <dd class="col-sm-9"><span class="badge bg-info">{{ $product->category->name }}</span></dd>

                    <dt class="col-sm-3">Price:</dt>
                    <dd class="col-sm-9"><strong class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</strong></dd>

                    <dt class="col-sm-3">Stock:</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->stock }} units
                        </span>
                    </dd>

                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">{{ $product->description ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Created:</dt>
                    <dd class="col-sm-9">{{ $product->created_at->format('d M Y H:i') }}</dd>

                    <dt class="col-sm-3">Updated:</dt>
                    <dd class="col-sm-9">{{ $product->updated_at->format('d M Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        @if($product->orderItems->count() > 0)
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Sales History</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->orderItems as $item)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $item->order) }}">#{{ $item->order->invoice_number }}</a></td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
