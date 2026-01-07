@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $category->name }}</h1>
    <div class="gap-2 d-flex">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">Edit</a>
        @if($category->products()->count() == 0)
        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">Delete</button>
        </form>
        @endif
        @endif
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Category Info -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Category Information</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Name:</dt>
                    <dd class="col-sm-9">{{ $category->name }}</dd>

                    <dt class="col-sm-3">Slug:</dt>
                    <dd class="col-sm-9"><code>{{ $category->slug }}</code></dd>

                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">{{ $category->description ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Created:</dt>
                    <dd class="col-sm-9">{{ $category->created_at->format('d M Y H:i') }}</dd>

                    <dt class="col-sm-3">Updated:</dt>
                    <dd class="col-sm-9">{{ $category->updated_at->format('d M Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        <!-- Products in Category -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Products in This Category ({{ $category->products()->count() }})</h6>
            </div>
            @if($category->products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="40" height="40" class="rounded">
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body">
                <p class="text-muted text-center py-4">No products in this category yet</p>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Stats -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Category Stats</h6>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    <strong>Total Products:</strong>
                    <br>
                    <span class="display-6 text-primary">{{ $category->products()->count() }}</span>
                </p>
                <hr>
                <p class="mb-0">
                    <strong>Total Stock Value:</strong>
                    <br>
                    <span class="text-success">
                        Rp {{ number_format(
                            $category->products()->sum(\DB::raw('price * stock')),
                            0, ',', '.'
                        ) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}" class="btn btn-primary btn-sm w-100 mb-2">
                    Add Product to This Category
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm w-100 mb-2">
                    Edit Category
                </a>
                @if($category->products()->count() == 0)
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Delete this category?')">
                        Delete Category
                    </button>
                </form>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
