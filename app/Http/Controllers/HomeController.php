<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Fitur Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter by Category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Pagination
        $products   = $query->paginate(12);
        $categories = Category::all();

        return view('welcome', compact('products', 'categories'));
    }

    // Method checkout dan myOrders akan ditambahkan di sini untuk user login
}
