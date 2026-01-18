@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $order->order_number }}</h1>
    <div class="gap-2 d-flex">
        @if(auth()->user()->isAdmin())
            @if($order->status === 'pending')
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning btn-sm">Edit</a>
            @endif
            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#statusModal">
                Change Status
            </button>
            @if($order->status === 'pending')
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this order?')">Delete</button>
            </form>
            @endif
        @endif
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Order Items</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="40" height="40" class="rounded">
                                    @endif
                                    <div>
                                        <strong>{{ $item->product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">No items</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Number of Items:</strong><br>{{ $order->orderItems->count() }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5 class="mb-3">
                            <strong>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Proof Card (untuk transfer) -->
        @if($order->payment_method === 'transfer')
        <div class="card mb-4">
            <div class="card-header @if($order->proof_status === 'approved') bg-success @elseif($order->proof_status === 'rejected') bg-danger @else bg-warning @endif text-white">
                <h6 class="mb-0"><i class="fas fa-file-invoice"></i> Payment Proof (Transfer)</h6>
            </div>
            <div class="card-body">
                @if($order->payment_proof)
                    <div class="mb-3">
                        <h6 class="text-muted small">Proof Status</h6>
                        <div>
                            @if($order->proof_status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($order->proof_status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                                @if($order->proof_rejection_reason)
                                    <div class="alert alert-danger mt-2 mb-0">
                                        <strong>Reason:</strong>
                                        <p class="mb-0 small">{{ $order->proof_rejection_reason }}</p>
                                    </div>
                                @endif
                            @else
                                <span class="badge bg-warning">Pending Review</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted small">Preview</h6>
                        <div>
                            @if(str_ends_with($order->payment_proof, '.pdf'))
                                <div class="border rounded p-3 text-center bg-light">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <p class="text-muted mb-2">PDF File</p>
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded" style="max-width: 100%; max-height: 300px;">
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle"></i> Payment proof not uploaded yet.
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Customer Information</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Name:</strong><br>
                    {{ $order->user->name }}
                </p>
                <p class="mb-2">
                    <strong>Email:</strong><br>
                    {{ $order->user->email }}
                </p>
                <p class="mb-0">
                    <strong>Phone:</strong><br>
                    {{ $order->user->phone ?? 'N/A' }}
                </p>
            </div>
        </div>

        <!-- Order Status -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Order Status</h6>
            </div>
            <div class="card-body">
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger'
                    ];
                @endphp
                <div class="mb-3">
                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} p-3">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>
                <p class="mb-0 text-muted small">
                    Last updated: {{ $order->updated_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Timeline</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Created:</dt>
                    <dd class="col-sm-7">{{ $order->created_at->format('d M Y H:i') }}</dd>

                    <dt class="col-sm-5">Updated:</dt>
                    <dd class="col-sm-7">{{ $order->updated_at->format('d M Y H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
@if(auth()->user()->isAdmin())
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    {{-- Proof status section for transfer payments --}}
                    @if($order->payment_method === 'transfer' && $order->payment_proof)
                    <div class="card border-warning mb-3">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h6 class="mb-0"><i class="fas fa-check-circle"></i> Verify Payment Proof</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="proof_status" class="form-label">Proof Status</label>
                                <select name="proof_status" id="proof_status" class="form-select">
                                    <option value="">No Change</option>
                                    <option value="approved" @if($order->proof_status === 'approved') selected @endif>Approve Proof</option>
                                    <option value="rejected" @if($order->proof_status === 'rejected') selected @endif>Reject Proof</option>
                                </select>
                            </div>

                            <div id="rejection-reason" style="display: none;">
                                <label for="proof_rejection_reason" class="form-label">Rejection Reason</label>
                                <textarea name="proof_rejection_reason" id="proof_rejection_reason" class="form-control" rows="3" placeholder="Explain why the payment proof is rejected...">{{ $order->proof_rejection_reason }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

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
