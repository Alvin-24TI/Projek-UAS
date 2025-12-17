<?php
// app/Http/Controllers/Staff/OrderController.php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Menampilkan list order untuk diproses staff
        // Filter: Staff mungkin hanya ingin lihat yang 'pending' atau 'processing'
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('staff.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Staff hanya boleh update status, tidak boleh ganti data master user/produk
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
