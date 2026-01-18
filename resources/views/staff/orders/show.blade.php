@extends('layouts.app')

@section('title', 'Detail Pesanan - Staff')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pesanan</h1>
        <a href="{{ route('staff.orders.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Invoice and Status Cards -->
        <div class="col-lg-4 mb-4">
            <!-- Invoice Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Nomor Pesanan</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-primary mb-0">{{ $order->order_number }}</h2>
                    <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                </div>
            </div>

            <!-- Status Card -->
            <div class="card shadow mb-4
                @switch($order->status)
                    @case('pending') border-warning @break
                    @case('processing') border-info @break
                    @case('completed') border-success @break
                    @case('cancelled') border-danger @break
                @endswitch">
                <div class="card-header py-3
                    @switch($order->status)
                        @case('pending') bg-warning @break
                        @case('processing') bg-info @break
                        @case('completed') bg-success @break
                        @case('cancelled') bg-danger @break
                    @endswitch">
                    <h6 class="m-0 font-weight-bold text-white">Status Pesanan</h6>
                </div>
                <div class="card-body text-center">
                    <h4 class="mb-3">
                        @switch($order->status)
                            @case('pending')
                                <span class="badge badge-warning px-3 py-2">Menunggu Pembayaran</span>
                                @break
                            @case('processing')
                                <span class="badge badge-info px-3 py-2">Sedang Diproses</span>
                                @break
                            @case('completed')
                                <span class="badge badge-success px-3 py-2">Selesai</span>
                                @break
                            @case('cancelled')
                                <span class="badge badge-danger px-3 py-2">Dibatalkan</span>
                                @break
                        @endswitch
                    </h4>
                    @if($order->status != 'completed' && $order->status != 'cancelled')
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="fas fa-edit"></i> Ubah Status
                        </button>
                    @endif
                </div>
            </div>

            <!-- Payment Info Card -->
            <div class="card shadow">
                <div class="card-header py-3 bg-info">
                    <h6 class="m-0 font-weight-bold text-white">Metode Pembayaran</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1">
                        @switch($order->payment_method)
                            @case('transfer')
                                <i class="fas fa-university text-info"></i> <strong>Transfer Bank</strong>
                                @break
                            @case('cod')
                                <i class="fas fa-money-bill-wave text-warning"></i> <strong>Cash On Delivery</strong>
                                @break
                            @case('card')
                                <i class="fas fa-credit-card text-success"></i> <strong>Kartu Kredit/Debit</strong>
                                @break
                        @endswitch
                    </p>
                    <hr>
                    <div class="text-center">
                        <h5 class="text-primary mb-0">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </h5>
                        <small class="text-muted">Total Pembayaran</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Customer Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">Nama Pelanggan</h6>
                            <p class="font-weight-bold">{{ $order->user->name }}</p>
                            <h6 class="text-muted small mt-3">Email</h6>
                            <p class="font-weight-bold">{{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">Role</h6>
                            <p class="font-weight-bold">
                                <span class="badge badge-primary">{{ ucfirst($order->user->role) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success">
                    <h6 class="m-0 font-weight-bold text-white">Informasi Pengiriman</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Nomor Telepon</h6>
                            <p class="font-weight-bold">{{ $order->shipping_phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small">Kota</h6>
                            <p class="font-weight-bold">{{ $order->shipping_city }}</p>
                        </div>
                    </div>
                    <h6 class="text-muted small">Alamat Lengkap</h6>
                    <p class="font-weight-bold">{{ $order->shipping_address }}</p>
                </div>
            </div>

<!-- Payment Proof Card (untuk transfer) -->
            @if($order->payment_method === 'transfer')
            <div class="card shadow mb-4">
                <div class="card-header py-3 @if($order->proof_status === 'approved') bg-success @elseif($order->proof_status === 'rejected') bg-danger @else bg-warning @endif">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-file-invoice"></i> Bukti Pembayaran Transfer
                    </h6>
                </div>
                <div class="card-body">
                    @if($order->payment_proof)
                        <div class="mb-3">
                            <h6 class="text-muted small">Status Bukti</h6>
                            <div class="mb-3">
                                @if($order->proof_status === 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($order->proof_status === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @if($order->proof_rejection_reason)
                                        <div class="alert alert-danger mt-2">
                                            <strong>Alasan Penolakan:</strong>
                                            <p class="mb-0">{{ $order->proof_rejection_reason }}</p>
                                        </div>
                                    @endif
                                @else
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted small">Preview Bukti Pembayaran</h6>
                            @if(str_ends_with($order->payment_proof, '.pdf'))
                                <div class="border rounded p-3 text-center bg-light">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <p class="text-muted">File PDF</p>
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded" style="max-width: 400px;">
                            @endif
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted small">Tanggal Upload</h6>
                            <p class="font-weight-bold">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i> Bukti pembayaran belum diupload oleh pelanggan.
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Order Items Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info">
                    <h6 class="m-0 font-weight-bold text-white">Item Pesanan ({{ $order->orderItems->count() }})</h6>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="d-flex align-items-start mb-4 pb-3 border-bottom">
                            <!-- Product Image -->
                            <div class="me-3">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                        alt="{{ $item->product->name }}"
                                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; display: block;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                        style="width: 80px; height: 80px; border-radius: 5px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-grow-1">
                                <h6 class="font-weight-bold mb-1">{{ $item->product->name }}</h6>
                                <small class="text-muted d-block">{{ $item->product->category->name }}</small>

                                <div class="row mt-2">
                                    <div class="col-6">
                                        <small class="text-muted">Harga Satuan</small>
                                        <p class="font-weight-bold mb-0">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Jumlah</small>
                                        <p class="font-weight-bold mb-0">{{ $item->quantity }} unit</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-end ms-3">
                                <small class="text-muted">Subtotal</small>
                                <p class="font-weight-bold text-primary mb-0">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <!-- Total -->
                    <div class="alert alert-light border-primary border-2 text-center mt-3">
                        <h5 class="text-primary mb-0">
                            Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-sm-flex">
                <a href="{{ route('staff.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
                @if($order->status != 'completed' && $order->status != 'cancelled')
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-edit"></i> Ubah Status
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
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
                            <label for="status" class="form-label fw-bold">Status Baru</label>
                            <select name="status" id="status" class="form-select" required>
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

                        {{-- Proof status section for transfer payments --}}
                        @if($order->payment_method === 'transfer' && $order->payment_proof)
                        <div class="card border-warning mb-3">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="mb-0"><i class="fas fa-check-circle"></i> Verifikasi Bukti Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="proof_status" class="form-label fw-bold">Status Bukti</label>
                                    <select name="proof_status" id="proof_status" class="form-select">
                                        <option value="">Tidak Ada Perubahan</option>
                                        <option value="approved" @if($order->proof_status === 'approved') selected @endif>Setujui Bukti</option>
                                        <option value="rejected" @if($order->proof_status === 'rejected') selected @endif>Tolak Bukti</option>
                                    </select>
                                </div>

                                <div id="rejection-reason" style="display: none;">
                                    <label for="proof_rejection_reason" class="form-label fw-bold">Alasan Penolakan</label>
                                    <textarea name="proof_rejection_reason" id="proof_rejection_reason" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan bukti pembayaran...">{{ $order->proof_rejection_reason }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const proofStatusSelect = document.getElementById('proof_status');
            const rejectionReasonDiv = document.getElementById('rejection-reason');
            
            if (proofStatusSelect) {
                proofStatusSelect.addEventListener('change', function() {
                    if (this.value === 'rejected') {
                        rejectionReasonDiv.style.display = 'block';
                        document.getElementById('proof_rejection_reason').required = true;
                    } else {
                        rejectionReasonDiv.style.display = 'none';
                        document.getElementById('proof_rejection_reason').required = false;
                    }
                });
                
                // Initial state
                if (proofStatusSelect.value === 'rejected') {
                    rejectionReasonDiv.style.display = 'block';
                }
            }
        });
    </script>
</div>
@endsection
