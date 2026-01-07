@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" step="0.01" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current image</small>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Current Image</h6>
            </div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                    <p class="text-muted small">{{ basename($product->image) }}</p>
                @else
                    <div class="text-muted py-5">
                        <p class="mt-2">No image uploaded</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Product Info</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Category:</strong><br>
                    <span class="badge bg-info">{{ $product->category->name }}</span>
                </p>
                <p class="mb-2">
                    <strong>Price:</strong><br>
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="mb-2">
                    <strong>Stock:</strong><br>
                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $product->stock }} pcs
                    </span>
                </p>
                <p class="mb-0">
                    <strong>Created:</strong><br>
                    {{ $product->created_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
