@extends('layouts.customer')

@section('title', 'Pesanan Saya - JBON STORE')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active">Pesanan Saya</li>
    </ol>
</nav>

<h2 class="mb-4">
    <i class="fas fa-history"></i> Pesanan Saya
</h2>

<!-- Success Message -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Status Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="btn-group" role="group">
                    <a href="{{ route('my-orders') }}"
                        class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-list"></i> Semua Pesanan
                    </a>
                    <a href="{{ route('my-orders', ['status' => 'pending']) }}"
                        class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-clock"></i> Menunggu
                    </a>
                    <a href="{{ route('my-orders', ['status' => 'processing']) }}"
                        class="btn {{ request('status') == 'processing' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-spinner"></i> Diproses
                    </a>
                    <a href="{{ route('my-orders', ['status' => 'completed']) }}"
                        class="btn {{ request('status') == 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-check"></i> Selesai
                    </a>
                    <a href="{{ route('my-orders', ['status' => 'cancelled']) }}"
                        class="btn {{ request('status') == 'cancelled' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-times"></i> Dibatalkan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders List -->
@if ($orders->count() > 0)
    @foreach ($orders as $order)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Order Info -->
                    <div class="col-lg-6">
                        <h5 class="mb-1">
                            <a href="{{ route('order.detail', $order->id) }}" class="text-decoration-none">
                                {{ $order->order_number }}
                            </a>
                        </h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-calendar-alt"></i> {{ $order->created_at->format('d M Y H:i') }}
                        </p>
                        <p class="mb-0">
                            <strong>{{ $order->orderItems->count() }}</strong> Produk |
                            <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-3">
                        <div class="text-center">
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-clock"></i> Menunggu Pembayaran
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-spinner"></i> Sedang Diproses
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle"></i> Selesai
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fas fa-times-circle"></i> Dibatalkan
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="col-lg-3 text-end">
                        <a href="{{ route('order.detail', $order->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>

                <!-- Product Preview -->
                <hr class="my-3">
                <div class="row">
                    @foreach ($order->orderItems->take(3) as $item)
                        <div class="col-4 col-md-2 mb-2 text-center">
                            @if ($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                    alt="{{ $item->product->name }}"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border-radius: 5px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                            <small class="d-block mt-1">{{ $item->quantity }}x</small>
                        </div>
                    @endforeach
                    @if ($order->orderItems->count() > 3)
                        <div class="col-4 col-md-2 mb-2 text-center">
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border-radius: 5px;">
                                <small class="text-muted">+{{ $order->orderItems->count() - 3 }} lagi</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </nav>
@else
    <!-- Empty State -->
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <h4 class="text-muted mb-3">Tidak Ada Pesanan</h4>
            <p class="text-muted mb-4">
                @if (request('status'))
                    Anda belum memiliki pesanan dengan status {{ $statuses[request('status')] }}.
                @else
                    Anda belum memiliki pesanan. Mulai belanja sekarang!
                @endif
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bags"></i> Mulai Belanja
            </a>
        </div>
    </div>
@endif
@endsection
