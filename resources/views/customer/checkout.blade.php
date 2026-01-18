@extends('layouts.customer')

@section('title', 'Checkout - JBON STORE')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.view') }}" class="text-decoration-none">Keranjang</a></li>
        <li class="breadcrumb-item active">Checkout</li>
    </ol>
</nav>

<h2 class="mb-4">
    <i class="fas fa-credit-card"></i> Checkout
</h2>

<!-- Alert Messages -->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Checkout Steps -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-light border">
            <div class="row text-center">
                <div class="col-4">
                    <span class="badge bg-primary rounded-pill" style="width: 30px; height: 30px; line-height: 30px;">1</span>
                    <p class="small mt-2">Keranjang</p>
                </div>
                <div class="col-4">
                    <span class="badge bg-primary rounded-pill" style="width: 30px; height: 30px; line-height: 30px;">2</span>
                    <p class="small mt-2">Checkout</p>
                </div>
                <div class="col-4">
                    <span class="badge bg-secondary rounded-pill" style="width: 30px; height: 30px; line-height: 30px;">3</span>
                    <p class="small mt-2">Konfirmasi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Checkout Form -->
    <div class="col-lg-8">
        <form action="{{ route('checkout') }}" method="POST">
            @csrf

            <!-- Shipping Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-truck"></i> Informasi Pengiriman
                    </h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label fw-bold">Alamat Pengiriman *</label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror"
                            id="shipping_address" name="shipping_address" rows="3"
                            placeholder="Masukkan alamat lengkap Anda" required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: Jl. Sudirman No. 123, Kota, Provinsi, 12345</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shipping_phone" class="form-label fw-bold">Nomor Telepon *</label>
                            <input type="tel" class="form-control @error('shipping_phone') is-invalid @enderror"
                                id="shipping_phone" name="shipping_phone"
                                placeholder="08xxxxxxxxxx" required value="{{ old('shipping_phone') }}">
                            @error('shipping_phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: 08123456789 atau +6281234567890</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="shipping_city" class="form-label fw-bold">Kota *</label>
                            <input type="text" class="form-control @error('shipping_city') is-invalid @enderror"
                                id="shipping_city" name="shipping_city"
                                placeholder="Masukkan nama kota" required value="{{ old('shipping_city') }}">
                            @error('shipping_city')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Saved Addresses (Optional) -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <small>Pastikan alamat pengiriman sudah benar untuk menghindari kesalahan pengiriman.</small>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-wallet"></i> Metode Pembayaran
                    </h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('payment_method') is-invalid @enderror"
                                type="radio" name="payment_method" id="payment_transfer" value="transfer" required
                                @if(old('payment_method') == 'transfer' || !old('payment_method')) checked @endif>
                            <label class="form-check-label" for="payment_transfer">
                                <strong>Transfer Bank</strong>
                                <br>
                                <small class="text-muted">Transfer ke rekening bank kami (BNI, BCA, Mandiri, CIMB)</small>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('payment_method') is-invalid @enderror"
                                type="radio" name="payment_method" id="payment_cod" value="cod" required
                                @if(old('payment_method') == 'cod') checked @endif>
                            <label class="form-check-label" for="payment_cod">
                                <strong>Cash On Delivery (COD)</strong>
                                <br>
                                <small class="text-muted">Bayar saat barang diterima di rumah Anda</small>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('payment_method') is-invalid @enderror"
                                type="radio" name="payment_method" id="payment_card" value="card" required
                                @if(old('payment_method') == 'card') checked @endif>
                            <label class="form-check-label" for="payment_card">
                                <strong>Kartu Kredit/Debit</strong>
                                <br>
                                <small class="text-muted">Pembayaran langsung menggunakan kartu Anda</small>
                            </label>
                        </div>
                    </div>

                    @error('payment_method')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <!-- Payment Proof Upload for Transfer -->
                    <div id="payment-proof-container" class="mt-4 p-4 border border-warning rounded-3" style="display: none;">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-file-upload text-warning"></i> Upload Bukti Pembayaran Transfer
                        </h6>
                        <div class="mb-3">
                            <label for="payment_proof" class="form-label fw-bold">Bukti Transfer Bank *</label>
                            <input type="file" class="form-control @error('payment_proof') is-invalid @enderror"
                                id="payment_proof" name="payment_proof" accept="image/*,.pdf"
                                placeholder="Pilih file bukti transfer">
                            @error('payment_proof')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Format: JPG, PNG, atau PDF. Ukuran maksimal: 5MB
                            </small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <small>
                                Silakan upload screenshot atau foto bukti transfer. Pastikan nama rekening tujuan terlihat jelas.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-right"></i> Lanjut ke Konfirmasi
                </button>
                <a href="{{ route('cart.view') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                </a>
            </div>
        </form>
    </div>

    <!-- Order Summary Sidebar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-receipt"></i> Ringkasan Pesanan
                </h5>
            </div>

            <div class="card-body">
                @php
                    $cart = session('cart', []);
                    $total = 0;
                    $itemCount = 0;
                @endphp

                @forelse ($cart as $productId => $quantity)
                    @php
                        $product = \App\Models\Product::find($productId);
                        if ($product) {
                            $subtotal = $product->price * $quantity;
                            $total += $subtotal;
                            $itemCount += $quantity;
                        }
                    @endphp
                    @if ($product)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-1">
                                <strong style="font-size: 0.95rem;">{{ $product->name }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">{{ $quantity }}x Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                <strong class="text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-muted text-center">Keranjang kosong</p>
                @endforelse

                <hr>

                <!-- Pricing Summary -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Jumlah Produk:</span>
                        <strong>{{ $itemCount }} item</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pajak:</span>
                        <span>Rp 0</span>
                    </div>
                </div>

                <hr>

                <!-- Total -->
                <div class="alert alert-light border-primary border-2">
                    <div class="d-flex justify-content-between">
                        <strong class="text-primary">Total Pembayaran:</strong>
                        <strong class="text-primary" style="font-size: 1.25rem;">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <!-- Info -->
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> <small>Mohon periksa kembali informasi pengiriman Anda sebelum melanjutkan.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/Hide payment proof upload based on payment method
    document.addEventListener('DOMContentLoaded', function() {
        const proofContainer = document.getElementById('payment-proof-container');
        const radioButtons = document.querySelectorAll('input[name="payment_method"]');
        
        function toggleProofContainer() {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
            if (selectedMethod === 'transfer') {
                proofContainer.style.display = 'block';
                document.getElementById('payment_proof').required = true;
            } else {
                proofContainer.style.display = 'none';
                document.getElementById('payment_proof').required = false;
                document.getElementById('payment_proof').value = '';
            }
        }
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', toggleProofContainer);
        });
        
        // Initial state
        toggleProofContainer();
    });
</script>
