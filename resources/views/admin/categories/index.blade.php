@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Categories</h1>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Categories Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <strong>{{ $category->name }}</strong>
                        @if($category->description)
                        <br>
                        <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $category->products_count }}</span>
                    </td>
                    <td>{{ $category->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info">View</a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                        @if($category->products_count == 0)
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                        @else
                        <button class="btn btn-sm btn-danger" disabled title="Cannot delete category with products">Delete</button>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No categories found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4 d-flex justify-content-center">
    <nav aria-label="Page navigation">
        {{ $categories->links('pagination::bootstrap-5') }}
    </nav>
</div>
@endsection
