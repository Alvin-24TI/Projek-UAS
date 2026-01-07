@extends('layouts.customer')

@section('title', 'Keranjang Belanja - Toko Online')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active">Keranjang Belanja</li>
    </ol>
</nav>

<h2 class="mb-4">
    <i class="fas fa-shopping-cart"></i> Keranjang Belanja
</h2>

<div class="row">
    <!-- Cart Items -->
    <div class="col-lg-8 mb-4">
        @if (count($items) > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <!-- Product Info -->
                                        <td>
                                            <div class="d-flex gap-3">
                                                @if ($item['product']->image)
                                                    <img src="{{ asset('storage/' . $item['product']->image) }}"
                                                        alt="{{ $item['product']->name }}"
                                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 60px; border-radius: 5px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('product.detail', $item['product']->slug) }}"
                                                        class="text-decoration-none text-dark fw-bold">
                                                        {{ $item['product']->name }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{{ $item['product']->category->name }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Price -->
                                        <td class="text-center align-middle">
                                            Rp {{ number_format($item['product']->price, 0, ',', '.') }}
                                        </td>

                                        <!-- Quantity -->
                                        <td class="text-center align-middle">
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <div class="input-group" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary btn-sm" type="submit"
                                                        onclick="this.form.quantity.value = Math.max(0, this.form.quantity.value - 1); return true;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" name="quantity" class="form-control text-center"
                                                        value="{{ $item['quantity'] }}" min="0" max="{{ $item['product']->stock }}"
                                                        style="font-size: 0.9rem;">
                                                    <button class="btn btn-outline-secondary btn-sm" type="submit"
                                                        onclick="this.form.quantity.value = Math.min({{ $item['product']->stock }}, parseInt(this.form.quantity.value) + 1); return true;">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>

                                        <!-- Subtotal -->
                                        <td class="text-right align-middle fw-bold">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </td>

                                        <!-- Remove -->
                                        <td class="text-center align-middle">
                                            <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Continue Shopping -->
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Lanjut Belanja
                </a>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Keranjang Anda Kosong</h4>
                    <p class="text-muted mb-4">Belum ada produk dalam keranjang. Mulai belanja sekarang!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bags"></i> Jelajahi Produk
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-receipt"></i> Ringkasan Pesanan
                </h5>
            </div>

            <div class="card-body">
                @if (count($items) > 0)
                    <!-- Summary Items -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jumlah Produk:</span>
                            <strong>{{ array_sum(array_map(fn($item) => $item['quantity'], $items)) }} item</strong>
                        </div>
                        <hr>
                    </div>

                    <!-- Pricing -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim:</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak:</span>
                            <span>Rp 0</span>
                        </div>
                        <hr>
                    </div>

                    <!-- Total -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <strong>Total Pembayaran:</strong>
                            <strong class="text-primary" style="font-size: 1.25rem;">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>

                    <!-- Checkout Buttons -->
                    @if (auth()->check())
                        <!-- Checkout Form Modal Toggle -->
                        <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                            data-bs-target="#checkoutModal">
                            <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-sign-in-alt"></i> Masuk untuk Checkout
                        </a>
                    @endif

                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100"
                            onclick="return confirm('Kosongkan seluruh keranjang?')">
                            <i class="fas fa-trash"></i> Kosongkan Keranjang
                        </button>
                    </form>
                @else
                    <!-- Empty Summary -->
                    <p class="text-muted text-center mb-0">Keranjang kosong</p>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Informasi Pengiriman & Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Alamat Pengiriman -->
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Alamat Pengiriman <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror"
                            id="shipping_address" name="shipping_address" rows="3"
                            placeholder="Masukkan alamat lengkap untuk pengiriman"
                            required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kota -->
                    <div class="mb-3">
                        <label for="shipping_city" class="form-label">Kota/Kabupaten <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('shipping_city') is-invalid @enderror"
                            id="shipping_city" name="shipping_city" placeholder="Contoh: Jakarta, Surabaya"
                            value="{{ old('shipping_city') }}" required>
                        @error('shipping_city')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="mb-3">
                        <label for="shipping_phone" class="form-label">Nomor Telepon <span
                                class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('shipping_phone') is-invalid @enderror"
                            id="shipping_phone" name="shipping_phone"
                            placeholder="Contoh: 081234567890 atau +6281234567890" value="{{ old('shipping_phone') }}"
                            required>
                        <small class="text-muted">Gunakan format 081... atau +62...</small>
                        @error('shipping_phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror"
                            id="payment_method" name="payment_method" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cod" {{ old('payment_method') === 'cod' ? 'selected' : '' }}>Bayar di Tempat (COD)</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Kartu Kredit</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i> Proses Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

