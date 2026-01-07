@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create New Order</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                    @csrf

                    <!-- Customer Selection -->
                    <div class="mb-4">
                        <label for="user_id" class="form-label">Customer</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                            <option value="">Select Customer</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Products Selection -->
                    <div class="mb-4">
                        <h6>Order Items</h6>
                        <div id="itemsContainer">
                            <div class="item-row mb-3" data-item="1">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Product</label>
                                        <select name="items[1][product_id]" class="form-control product-select @error('items.0.product_id') is-invalid @enderror" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" data-price="{{ $product->price }}">
                                                {{ $product->name }} (Stock: {{ $product->stock }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('items.0.product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" name="items[1][quantity]" class="form-control quantity-input @error('items.0.quantity') is-invalid @enderror" min="1" value="1" required>
                                        @error('items.0.quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger w-100 remove-item" style="display:none;">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" id="addItem">+ Add Product</button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Order</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-light">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <div id="summaryItems">
                    <p class="text-muted">No items selected</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong id="totalPrice">Rp 0</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let itemCount = 1;

document.getElementById('addItem').addEventListener('click', function() {
    itemCount++;
    const container = document.getElementById('itemsContainer');
    const newItem = document.createElement('div');
    newItem.className = 'item-row mb-3';
    newItem.setAttribute('data-item', itemCount);
    newItem.innerHTML = `
        <div class="row g-2">
            <div class="col-md-6">
                <select name="items[${itemCount}][product_id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" data-price="{{ $product->price }}">
                        {{ $product->name }} (Stock: {{ $product->stock }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity-input" min="1" value="1" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100 remove-item">Remove</button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    attachEventListeners();
    updateSummary();
});

function attachEventListeners() {
    document.querySelectorAll('.product-select').forEach(select => {
        select.addEventListener('change', updateSummary);
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', updateSummary);
    });

    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('.item-row').remove();
            updateSummary();
        });
    });
}

function updateSummary() {
    let total = 0;
    let summaryHTML = '';
    let itemCount = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');

        if (select.value && quantityInput.value) {
            const option = select.querySelector(`option[value="${select.value}"]`);
            const price = parseInt(option.dataset.price);
            const quantity = parseInt(quantityInput.value);
            const subtotal = price * quantity;

            total += subtotal;
            itemCount++;

            summaryHTML += `
                <div class="mb-2">
                    <small class="text-muted">${option.text.split('(')[0].trim()}</small>
                    <br>
                    <small>${quantity}x Rp ${price.toLocaleString('id-ID')}</small>
                </div>
            `;
        }

        // Show/hide remove button
        const removeBtn = row.querySelector('.remove-item');
        if (document.querySelectorAll('.item-row').length > 1) {
            removeBtn.style.display = 'block';
        }
    });

    document.getElementById('summaryItems').innerHTML = summaryHTML || '<p class="text-muted">No items selected</p>';
    document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Initial setup
attachEventListeners();
updateSummary();
</script>

@endsection
