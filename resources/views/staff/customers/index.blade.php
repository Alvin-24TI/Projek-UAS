@extends('layouts.app')

@section('title', 'Daftar Customer')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Customer</h1>
    </div>

    <!-- DataTable -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Total Customer: {{ $customers->total() }}</h6>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Total Pesanan</th>
                                <th>Total Pembelian</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = ($customers->currentPage() - 1) * $customers->perPage() + 1; @endphp
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $customer->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $customer->orders_count }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $totalSpent = $customer->orders()->sum('total_amount');
                                        @endphp
                                        Rp {{ number_format($totalSpent, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <small>{{ $customer->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('staff.customers.show', $customer->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
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
                    {{ $customers->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada customer terdaftar
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
