@extends('layouts.customer')

@section('title', 'Detail Pesanan - JBON STORE')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active">{{ $order->order_number }}</li>
    </ol>
</nav>

<h2 class="mb-4">
    <i class="fas fa-receipt"></i> Detail Pesanan
</h2>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Invoice Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="text-muted mb-1">Nomor Pesanan</h5>
                        <h2 class="text-primary mb-0">{{ $order->order_number }}</h2>
                    </div>
                    <div class="col text-end">
                        <p class="text-muted mb-1">Tanggal Pesanan</p>
                        <p class="fw-bold mb-0">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-tasks"></i> Status Pesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <div class="mb-2">
                            <span class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; line-height: 40px; font-size: 1.2rem;">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                        <p class="small fw-bold">Pesanan Diterima</p>
                        <p class="text-muted small">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="col">
                        <div class="mb-2">
                            <span class="badge {{ $order->status != 'pending' ? 'bg-primary' : 'bg-light text-dark' }} rounded-circle"
                                style="width: 40px; height: 40px; line-height: 40px; font-size: 1.2rem;">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                        <p class="small fw-bold">Diproses</p>
                        <p class="text-muted small">
                            @if ($order->status != 'pending')
                                {{ $order->updated_at->format('d M Y') }}
                            @else
                                Menunggu...
                            @endif
                        </p>
                    </div>
                    <div class="col">
                        <div class="mb-2">
                            <span class="badge {{ in_array($order->status, ['processing', 'completed']) ? 'bg-primary' : 'bg-light text-dark' }} rounded-circle"
                                style="width: 40px; height: 40px; line-height: 40px; font-size: 1.2rem;">
                                <i class="fas fa-truck"></i>
                            </span>
                        </div>
                        <p class="small fw-bold">Pengiriman</p>
                        <p class="text-muted small">Menunggu...</p>
                    </div>
                    <div class="col">
                        <div class="mb-2">
                            <span class="badge {{ $order->status == 'completed' ? 'bg-primary' : 'bg-light text-dark' }} rounded-circle"
                                style="width: 40px; height: 40px; line-height: 40px; font-size: 1.2rem;">
                                <i class="fas fa-home"></i>
                            </span>
                        </div>
                        <p class="small fw-bold">Diterima</p>
                        <p class="text-muted small">
                            @if ($order->status == 'completed')
                                {{ $order->updated_at->format('d M Y') }}
                            @else
                                Menunggu...
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt"></i> Informasi Pengiriman
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Nama Penerima</h6>
                        <p class="fw-bold">{{ $order->user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Nomor Telepon</h6>
                        <p class="fw-bold">{{ $order->shipping_phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Kota</h6>
                        <p class="fw-bold">{{ $order->shipping_city }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Alamat Lengkap</h6>
                        <p class="fw-bold">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-bag"></i> Detail Produk ({{ $order->orderItems->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex gap-3">
                                            @if ($item->product->image)
                                                <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px; border-radius: 5px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('product.detail', $item->product->slug) }}"
                                                    class="text-decoration-none text-dark fw-bold">
                                                    {{ $item->product->name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-right align-middle fw-bold">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-grid gap-2 d-sm-flex">
            <a href="{{ route('my-orders') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bags"></i> Lanjut Belanja
            </a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header
                @switch($order->status)
                    @case('pending') bg-warning @break
                    @case('processing') bg-info @break
                    @case('completed') bg-success @break
                    @case('cancelled') bg-danger @break
                @endswitch
                text-white">
                <h5 class="mb-0">
                    @switch($order->status)
                        @case('pending')
                            <i class="fas fa-clock"></i> Menunggu Pembayaran
                            @break
                        @case('processing')
                            <i class="fas fa-spinner"></i> Sedang Diproses
                            @break
                        @case('completed')
                            <i class="fas fa-check-circle"></i> Selesai
                            @break
                        @case('cancelled')
                            <i class="fas fa-times-circle"></i> Dibatalkan
                            @break
                    @endswitch
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Status saat ini</p>
                <p class="fw-bold mb-0">
                    @switch($order->status)
                        @case('pending') Menunggu konfirmasi pembayaran @break
                        @case('processing') Kami sedang mempersiapkan pesanan Anda @break
                        @case('completed') Pesanan telah selesai diterima @break
                        @case('cancelled') Pesanan telah dibatalkan @break
                    @endswitch
                </p>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-wallet"></i> Informasi Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Metode Pembayaran</h6>
                    <p class="fw-bold mb-0">
                        @switch($order->payment_method)
                            @case('transfer')
                                <i class="fas fa-university"></i> Transfer Bank
                                @break
                            @case('cod')
                                <i class="fas fa-money-bill-wave"></i> Cash On Delivery (COD)
                                @break
                            @case('card')
                                <i class="fas fa-credit-card"></i> Kartu Kredit/Debit
                                @break
                        @endswitch
                    </p>
                </div>

                <hr>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pajak:</span>
                        <span>Rp 0</span>
                    </div>
                </div>

                <hr>

                <div class="alert alert-light border-primary border-2">
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong class="text-primary" style="font-size: 1.25rem;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="mb-2">Butuh Bantuan?</h6>
                <p class="small text-muted mb-3">Hubungi customer service kami untuk bantuan lebih lanjut</p>
                <a href="#" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-phone"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
