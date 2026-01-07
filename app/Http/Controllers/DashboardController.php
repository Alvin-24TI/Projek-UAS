<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Pelanggan;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = Pelanggan::count();
        $totalCategories = Category::count();

        return view('dashboard', compact('totalProducts', 'totalOrders', 'totalCustomers', 'totalCategories'));
    }
}
