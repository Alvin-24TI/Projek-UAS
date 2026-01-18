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
        $orders = Order::with('user', 'orderItems.product')->orderBy('created_at', 'desc')->paginate(15);
        return view('staff.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Tampilkan detail order lengkap
        $order->load('user', 'orderItems.product');
        return view('staff.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Staff bisa update status dan juga mengelola bukti pembayaran
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'proof_status' => 'nullable|in:pending,approved,rejected',
            'proof_rejection_reason' => 'nullable|string'
        ]);

        $updateData = ['status' => $request->status];

        // Handle proof status update
        if ($request->proof_status) {
            $updateData['proof_status'] = $request->proof_status;
            
            if ($request->proof_status === 'rejected') {
                $updateData['proof_rejection_reason'] = $request->proof_rejection_reason;
                // Jika bukti ditolak, ubah status order ke cancelled
                $updateData['status'] = 'cancelled';
            } else if ($request->proof_status === 'approved') {
                // Jika bukti disetujui, ubah status ke processing
                $updateData['status'] = 'processing';
            }
        }

        $order->update($updateData);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
