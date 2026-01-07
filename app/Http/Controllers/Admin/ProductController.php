<?php
// app/Http/Controllers/Admin/ProductController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        // Pagination + Search Filter + Category Filter
        $query = Product::with('category');

        if(request()->search) {
            $query->where('name', 'like', '%'.request()->search.'%')
                  ->orWhere('description', 'like', '%'.request()->search.'%');
        }

        if(request()->category) {
            $query->where('category_id', request()->category);
        }

        $products = $query->orderByDesc('created_at')->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in database
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        // Handle File Upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
                       ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category', 'orderItems');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in database
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        // Handle File Upload (Delete old image if new one uploaded)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
                       ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified product from database
     */
    public function destroy(Product $product)
    {
        // Delete image file if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                       ->with('success', 'Produk berhasil dihapus');
    }
}
