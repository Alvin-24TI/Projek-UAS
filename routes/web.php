<?php
// routes/web.php
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Staff\OrderController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// 1. Guest / Public Access (Search, Filter, View Products)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.detail');
// Auth Routes (Login/Register standard Laravel)
// ...
// 2. Authenticated Users (Customer can Buy)
Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [HomeController::class, 'checkout'])->name('checkout');
    Route::get('/my-orders', [HomeController::class, 'myOrders'])->name('my-orders');
});
// 3. Admin Routes (Manage Master Data: Products, Categories)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {return view('admin.dashboard');})->name('dashboard');
    Route::resource('products', ProductController::class); // CRUD Produk
                                                           // Route::resource('categories', CategoryController::class);
});
// 4. Staff Routes (Manage Orders: Process status)
Route::middleware(['auth', 'role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    // Admin juga bisa akses area staff biasanya, atau dipisah strict
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});
Route::view('/dashboard', 'dashboard')->name('dashboard');
