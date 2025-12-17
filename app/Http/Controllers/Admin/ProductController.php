<?php
// app/Http/Controllers/Admin/ProductController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Pagination + Search Filter
        $query = Product::with('category');
        if($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        $products = $query->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        // Form Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Upload Validation
        ]);

        $data = $request->all();
        $data['slug'] = \Str::slug($request->name);

        // Upload File Logic
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // ... method update (mirip store, handle delete old image), destroy, edit, create
}
