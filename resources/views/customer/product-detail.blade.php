@extends('layouts.customer')

@section('title', $product->name . ' - Toko Online')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('home', ['category' => $product->category_id]) }}"
            class="text-decoration-none">{{ $product->category->name }}</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Product Image -->
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}" class="card-img-top" style="height: 500px; object-fit: cover;">
            @else
                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 500px;">
                    <i class="fas fa-image fa-5x text-muted"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Details -->
    <div class="col-lg-7">
        <!-- Category & Rating -->
        <div class="mb-3">
            <span class="badge bg-primary">{{ $product->category->name }}</span>
        </div>

        <!-- Product Name -->
        <h1 class="mb-2" style="font-size: 1.75rem; font-weight: 700;">{{ $product->name }}</h1>

        <!-- Divider -->
        <hr>

        <!-- Price -->
        <div class="mb-4">
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Harga</p>
            <h2 class="text-primary" style="font-size: 2rem; font-weight: 700;">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </h2>
        </div>

        <!-- Description -->
        @if ($product->description)
            <div class="mb-4">
                <h5 class="fw-bold mb-2" style="font-size: 1rem;">Deskripsi Produk</h5>
                <p class="text-muted" style="line-height: 1.6; font-size: 0.95rem;">
                    {{ $product->description }}
                </p>
            </div>
        @endif

        <!-- Divider -->
        <hr>

        <!-- Add to Cart Form -->
        @if ($product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="mb-3">
                    <label for="quantity" class="form-label fw-bold">Jumlah Pesanan</label>
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" id="decreaseBtn">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control text-center" id="quantity" name="quantity"
                            value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-secondary" type="button" id="increaseBtn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <small class="text-muted">Maksimal: {{ $product->stock }} item</small>
                </div>

                <div class="d-grid gap-2 d-sm-flex">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
            </form>
        @else
            <div class="alert alert-danger mb-4">
                <i class="fas fa-ban"></i> Produk ini sedang tidak tersedia
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        @endif

        <!-- Product Details Info -->
        <div class="card border-light mt-4">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3">Informasi Produk</h5>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td style="width: 40%; color: #666;">Kategori</td>
                        <td class="fw-bold">{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%; color: #666;">Status Stok</td>
                        <td>
                            @if ($product->stock > 0)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%; color: #666;">Ditambahkan</td>
                        <td class="fw-bold">{{ $product->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseBtn');
    const increaseBtn = document.getElementById('increaseBtn');
    const maxStock = {{ $product->stock }};

    decreaseBtn.addEventListener('click', function() {
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    });

    increaseBtn.addEventListener('click', function() {
        if (parseInt(quantityInput.value) < maxStock) {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }
    });

    quantityInput.addEventListener('change', function() {
        if (parseInt(this.value) > maxStock) {
            this.value = maxStock;
        }
        if (parseInt(this.value) < 1) {
            this.value = 1;
        }
    });
</script>
@endpush
@endsection
