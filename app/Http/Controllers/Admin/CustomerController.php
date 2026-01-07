<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')->with('error', 'User bukan customer');
        }
        $orders = $customer->orders()->with('orderItems.product')->latest()->paginate(10);
        return view('admin.customers.show', compact('customer', 'orders'));
    }
}
