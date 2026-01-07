@extends('layouts.app')

@section('title', 'Detail Customer - ' . $customer->name)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Customer</h1>
        <a href="{{ route('staff.customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-circle"></i> Informasi Customer
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Nama</label>
                        <p class="m-0 h6">{{ $customer->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Email</label>
                        <p class="m-0">
                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Status</label>
                        <p class="m-0">
                            <span class="badge bg-success">{{ ucfirst($customer->role) }}</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Terdaftar</label>
                        <p class="m-0">{{ $customer->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-0 font-weight-bold text-primary">{{ $orders->total() }}</div>
                                <p class="small text-muted">Total Pesanan</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-0 font-weight-bold text-success">
                                    Rp {{ number_format($customer->orders()->sum('total_amount'), 0, ',', '.') }}
                                </div>
                                <p class="small text-muted">Total Pembelian</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Orders -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-shopping-bag"></i> Pesanan Customer
                    </h6>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Item</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>{{ $order->order_number }}</strong>
                                            </td>
                                            <td>
                                                <small>{{ $order->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $order->orderItems->count() }} item</small>
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $statusBadge = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    $statusLabel = [
                                                        'pending' => 'Menunggu',
                                                        'processing' => 'Diproses',
                                                        'completed' => 'Selesai',
                                                        'cancelled' => 'Dibatalkan'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusBadge[$order->status] ?? 'secondary' }}">
                                                    {{ $statusLabel[$order->status] ?? ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('staff.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-3">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Customer belum memiliki pesanan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
