<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show homepage with product listing
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        // Fitur Search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter by Category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by stock availability
        if ($request->has('stock') && $request->stock == 'available') {
            $query->where('stock', '>', 0);
        }

        // Pagination
        $products = $query->paginate(12);

        // Get categories with dynamic product count based on current filters
        $categories = Category::all()->map(function($category) use ($request) {
            // Build query for this category
            $catQuery = Product::where('category_id', $category->id);

            // Apply same filters as main query
            if ($request->has('search') && $request->search) {
                $catQuery->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('stock') && $request->stock == 'available') {
                $catQuery->where('stock', '>', 0);
            }

            // Count products in this category with filters applied
            $category->products_count = $catQuery->count();
            return $category;
        });

        return view('welcome', compact('products', 'categories'));
    }

    /**
     * Show product detail
     */
    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $categories = Category::all();

        return view('customer.product-detail', compact('product', 'categories'));
    }

    /**
     * Checkout - Create order from cart
     */
    public function checkout(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk untuk melanjutkan checkout');
        }

        $cart = session('cart', []);

        // Validate cart is not empty
        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Keranjang belanja Anda kosong');
        }

        // Validate request
        $rules = [
            'shipping_address' => 'nullable|string',
            'shipping_phone' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'payment_method' => 'nullable|in:transfer,cod,card'
        ];

        // Add payment_proof validation if payment method is transfer
        if ($request->payment_method === 'transfer') {
            $rules['payment_proof'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:5120';
        } else {
            $rules['payment_proof'] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120';
        }

        $validated = $request->validate($rules, [], [
            'shipping_address' => 'Alamat Pengiriman',
            'shipping_phone' => 'Nomor Telepon',
            'shipping_city' => 'Kota',
            'payment_method' => 'Metode Pembayaran',
            'payment_proof' => 'Bukti Pembayaran'
        ]);

        $user = auth()->user();
        $totalPrice = 0;
        $orderItems = [];

        // Validate all products and calculate total
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (!$product) {
                return back()->with('error', 'Produk tidak valid dalam keranjang');
            }

            if ($quantity > $product->stock) {
                return back()->with('error', 'Stok ' . $product->name . ' tidak mencukupi. Stok tersedia: ' . $product->stock);
            }

            $orderItems[$productId] = [
                'quantity' => $quantity,
                'price' => $product->price
            ];

            $totalPrice += $product->price * $quantity;
        }

        try {
            // Create order
            $orderNumber = 'ORD-' . now()->format('YmdHis') . '-' . rand(100, 999);

            $orderData = [
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $totalPrice,
                'shipping_address' => $validated['shipping_address'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_city' => $validated['shipping_city'],
                'payment_method' => $validated['payment_method']
            ];

            // Handle payment proof upload
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $path = $file->store('payment-proofs', 'public');
                $orderData['payment_proof'] = $path;
                $orderData['proof_status'] = 'pending';
            }

            $order = Order::create($orderData);

            // Create order items and deduct stock
            foreach ($orderItems as $productId => $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price']
                ]);

                // Deduct stock
                $product = Product::find($productId);
                $product->update(['stock' => $product->stock - $itemData['quantity']]);
            }

            // Clear cart
            session(['cart' => []]);

            return redirect()->route('my-orders')
                ->with('success', 'Pesanan berhasil dibuat! Nomor Order: ' . $orderNumber);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Order confirmation page
     */
    public function orderConfirmation($orderId)
    {
        $order = Order::with(['user', 'orderItems.product'])->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan');
        }

        // Verify order belongs to authenticated user
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        $categories = Category::all();
        return view('customer.order-confirmation', compact('order', 'categories'));
    }

    /**
     * Show user's orders
     */
    public function myOrders(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk untuk melihat pesanan Anda');
        }

        $query = Order::where('user_id', auth()->id())->with(['orderItems.product'])->orderByDesc('created_at');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);
        $categories = Category::all();
        $statuses = ['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];

        return view('customer.my-orders', compact('orders', 'categories', 'statuses'));
    }

    /**
     * Show order detail
     */
    public function orderDetail($orderId)
    {
        $order = Order::with(['user', 'orderItems.product'])->find($orderId);

        if (!$order) {
            return redirect()->route('my-orders')->with('error', 'Pesanan tidak ditemukan');
        }

        // Verify order belongs to authenticated user
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->route('my-orders')->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        $categories = Category::all();
        return view('customer.order-detail', compact('order', 'categories'));
    }
}
