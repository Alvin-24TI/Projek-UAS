<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Show cart view
     */
    public function view()
    {
        $categories = \App\Models\Category::all();
        $cart = session('cart', []);

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity
                ];
                $total += $product->price * $quantity;
            }
        }

        return view('customer.cart', compact('items', 'total', 'categories'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($validated['product_id']);

        // Check if product has enough stock
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        // Get current cart
        $cart = session('cart', []);

        // Add or update product in cart
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id] + $validated['quantity'];

            // Check if total quantity exceeds stock
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Total pesanan melebihi stok tersedia. Stok tersedia: ' . $product->stock);
            }

            $cart[$product->id] = $newQuantity;
        } else {
            $cart[$product->id] = $validated['quantity'];
        }

        // Save cart to session
        session(['cart' => $cart]);

        return back()->with('success', $product->name . ' ditambahkan ke keranjang!');
    }

    /**
     * Update quantity in cart
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $product = Product::find($validated['product_id']);
        $cart = session('cart', []);

        if ($validated['quantity'] == 0) {
            // Remove item from cart
            unset($cart[$product->id]);
        } else {
            // Check stock
            if ($product->stock < $validated['quantity']) {
                return back()->with('error', 'Stok produk tidak mencukupi. Stok tersedia: ' . $product->stock);
            }

            $cart[$product->id] = $validated['quantity'];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Keranjang telah diperbarui!');
    }

    /**
     * Remove item from cart
     */
    public function remove($productId)
    {
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session(['cart' => []]);

        return back()->with('success', 'Keranjang telah dikosongkan!');
    }

    /**
     * Get cart count for navbar
     */
    public static function getCartCount()
    {
        $cart = session('cart', []);
        return array_sum($cart);
    }
}
