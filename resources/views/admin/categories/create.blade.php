@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add Category</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g., Electronics" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Category description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Category</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Category Tips</h6>
            </div>
            <div class="card-body">
                <p class="small"><strong>✓ Good Names:</strong></p>
                <ul class="small">
                    <li>Electronics</li>
                    <li>Clothing & Fashion</li>
                    <li>Home & Garden</li>
                    <li>Sports & Outdoors</li>
                </ul>
                <p class="small mt-3"><strong>✓ Tips:</strong></p>
                <ul class="small">
                    <li>Keep names short (2-4 words)</li>
                    <li>Be clear and descriptive</li>
                    <li>Avoid special characters</li>
                    <li>Slug will be auto-generated</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
