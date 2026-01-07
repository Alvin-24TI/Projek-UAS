@extends('layouts.customer')

@section('title', 'JBON STORE - Berbelanja Produk Berkualitas')

@section('content')
<div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
        <!-- Category Filter -->
        <div class="sidebar-card">
            <h5 class="sidebar-title">Kategori Produk</h5>
            <ul class="category-list">
                <li class="category-item">
                    <a href="{{ route('home') }}"
                        class="category-link {{ !request('category') ? 'active' : '' }}">
                        Semua Kategori
                    </a>
                </li>
                @foreach ($categories as $category)
                    <li class="category-item">
                        <a href="{{ route('home', ['category' => $category->id]) }}"
                            class="category-link {{ request('category') == $category->id ? 'active' : '' }}">
                            {{ $category->name }}
                            <small class="text-muted">({{ $category->products_count ?? 0 }})</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <!-- Header Section -->
        <div class="mb-4">
            <h2 class="mb-2">
                <i class="fas fa-shopping-bags"></i>
                @if(request('search'))
                    Hasil Pencarian: "{{ request('search') }}"
                @elseif(request('category'))
                    {{ $categories->find(request('category'))->name ?? 'Kategori' }}
                @else
                    Semua Produk
                @endif
            </h2>
            <p class="text-muted">Temukan produk yang Anda cari dengan harga terbaik</p>
        </div>

        <!-- Product Grid -->
        @if ($products->count() > 0)
            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('product.detail', $product->slug) }}" class="product-card text-decoration-none" style="cursor: pointer;">
                            <!-- Product Image -->
                            <div class="position-relative">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="product-body">
                                <div class="product-name">
                                    {{ $product->name }}
                                </div>

                                <p class="product-category">
                                    <small>{{ $product->category->name ?? 'Tidak ada kategori' }}</small>
                                </p>

                                <p class="product-price">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>

                                <div class="product-stock">
                                    @if ($product->stock > 0)
                                        <span class="stock-badge stock-in">
                                            <i class="fas fa-check-circle"></i> Stok: {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="stock-badge stock-out">
                                            <i class="fas fa-times-circle"></i> Habis
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="product-actions">
                                    @if ($product->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" style="width: 100%;" onclick="event.stopPropagation();">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm-custom w-100">
                                                Beli
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm-custom w-100" disabled>
                                            Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </nav>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Produk Tidak Ditemukan</h4>
                <p class="text-muted mb-3">Maaf, kami tidak menemukan produk yang sesuai dengan pencarian Anda.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>
</div>

@push('js')
<script>
    // Handle stock filter checkbox
    document.getElementById('stockCheck').addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        if (this.checked) {
            params.set('stock', 'available');
        } else {
            params.delete('stock');
        }
        window.location.search = params.toString();
    });
</script>
@endpush
@endsection
