@extends('layouts.app')

@section('title', 'Manajemen Pesanan - Staff')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pesanan</h1>
        <a href="{{ route('staff.orders.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Pesanan Baru
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.orders.index') }}" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Cari No. Invoice atau Pelanggan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Cards -->
    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card shadow h-100">
                        <!-- Card Header with Status -->
                        <div class="card-header py-3 d-flex justify-content-between align-items-center
                            @switch($order->status)
                                @case('pending') bg-warning @break
                                @case('processing') bg-info @break
                                @case('completed') bg-success @break
                                @case('cancelled') bg-danger @break
                            @endswitch">
                            <h6 class="m-0 font-weight-bold text-white">
                                {{ $order->order_number }}
                            </h6>
                            <span class="badge badge-light">
                                @switch($order->status)
                                    @case('pending') Menunggu @break
                                    @case('processing') Diproses @break
                                    @case('completed') Selesai @break
                                    @case('cancelled') Dibatalkan @break
                                @endswitch
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Customer Info -->
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Pelanggan</h6>
                                <p class="mb-0 font-weight-bold">{{ $order->user->name }}</p>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </div>

                            <hr>

                            <!-- Order Info -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <h6 class="text-muted small mb-1">Tanggal Pesanan</h6>
                                    <p class="mb-0 font-weight-bold">{{ $order->created_at->format('d M Y') }}</p>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted small mb-1">Total Pesanan</h6>
                                    <p class="mb-0 font-weight-bold text-primary">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <!-- Items Count -->
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Jumlah Item</h6>
                                <p class="mb-0 font-weight-bold">
                                    {{ $order->orderItems->count() }} produk
                                </p>
                            </div>

                            <!-- Shipping City -->
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Kota Pengiriman</h6>
                                <p class="mb-0 font-weight-bold">{{ $order->shipping_city ?? '-' }}</p>
                            </div>

                            <hr>

                            <!-- Payment Method -->
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Metode Pembayaran</h6>
                                <p class="mb-0">
                                    @switch($order->payment_method)
                                        @case('transfer')
                                            <span class="badge badge-info"><i class="fas fa-university"></i> Transfer Bank</span>
                                            @break
                                        @case('cod')
                                            <span class="badge badge-warning"><i class="fas fa-money-bill-wave"></i> COD</span>
                                            @break
                                        @case('card')
                                            <span class="badge badge-success"><i class="fas fa-credit-card"></i> Kartu</span>
                                            @break
                                    @endswitch
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer with Actions -->
                        <div class="card-footer bg-light">
                            <div class="d-grid gap-2">
                                <a href="{{ route('staff.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                @if($order->status != 'completed' && $order->status != 'cancelled')
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                        <i class="fas fa-edit"></i> Ubah Status
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Update Modal -->
                    <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('staff.orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah Status Pesanan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="status{{ $order->id }}" class="form-label fw-bold">Status Baru</label>
                                            <select name="status" id="status{{ $order->id }}" class="form-select" required>
                                                <option value="">Pilih Status</option>
                                                @if($order->status == 'pending')
                                                    <option value="processing">Diproses</option>
                                                    <option value="cancelled">Dibatalkan</option>
                                                @elseif($order->status == 'processing')
                                                    <option value="completed">Selesai</option>
                                                    <option value="cancelled">Dibatalkan</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            {{ $orders->links('pagination::bootstrap-5') }}
        </nav>
    @else
        <!-- Empty State -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak Ada Pesanan</h4>
                <p class="text-muted mb-0">Belum ada pesanan yang sesuai dengan filter Anda</p>
            </div>
        </div>
    @endif
</div>
@endsection
