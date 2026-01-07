<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems.product');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by order number
        if ($request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . request()->search . '%');
                  });
        }

        $orders = $query->orderByDesc('created_at')->paginate(10);
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $users = \App\Models\User::where('role', 'customer')->get();
        $products = Product::where('stock', '>', 0)->get();

        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Store a newly created order in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Generate order number
            $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);

            $totalAmount = 0;
            $items = $validated['items'];

            // Calculate total and validate stock
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    return back()->with('error', "Stok {$product->name} tidak cukup");
                }
                $totalAmount += $product->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items and update stock
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                // Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            return redirect()->route('admin.orders.show', $order)
                           ->with('success', 'Pesanan berhasil dibuat dengan nomor order: ' . $orderNumber);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        // Can only edit pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders.show', $order)
                           ->with('error', 'Hanya pesanan dengan status pending yang dapat diedit');
        }

        $order->load('user', 'orderItems.product');
        $users = \App\Models\User::where('role', 'customer')->get();
        $products = Product::all();

        return view('admin.orders.edit', compact('order', 'users', 'products'));
    }

    /**
     * Update the specified order in database
     */
    public function update(Request $request, Order $order)
    {
        // Only allow editing pending orders
        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan dengan status pending yang dapat diubah');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'items' => 'sometimes|array',
            'items.*.product_id' => 'sometimes|exists:products,id',
            'items.*.quantity' => 'sometimes|integer|min:1',
        ]);

        try {
            // Update status
            $order->update(['status' => $validated['status']]);

            // If items are provided, update them
            if (isset($validated['items']) && count($validated['items']) > 0) {
                // Restore original stock
                foreach ($order->orderItems as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                // Delete old items
                $order->orderItems()->delete();

                // Recalculate total
                $totalPrice = 0;
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        return back()->with('error', "Stok {$product->name} tidak cukup");
                    }

                    $order->orderItems()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    $totalPrice += $product->price * $item['quantity'];
                    $product->decrement('stock', $item['quantity']);
                }

                $order->update(['total_amount' => $totalPrice]);
            }

            return redirect()->route('admin.orders.show', $order)
                           ->with('success', 'Pesanan berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order from database
     */
    public function destroy(Order $order)
    {
        // Only allow deleting pending orders
        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan dengan status pending yang dapat dihapus');
        }

        try {
            // Restore stock before deleting
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $order->delete();

            return redirect()->route('admin.orders.index')
                           ->with('success', 'Pesanan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }
}
