<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalCategories = Category::count();

        return view('dashboard', compact('totalProducts', 'totalOrders', 'totalCustomers', 'totalCategories'));
    }
}
