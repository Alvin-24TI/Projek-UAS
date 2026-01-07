<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.detail');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/checkout', [HomeController::class, 'checkout'])->name('checkout');
    Route::get('/my-orders', [HomeController::class, 'myOrders'])->name('my-orders');
});

// Admin Routes - Can access everything
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class);
});

// Staff Routes - Can access orders and update status
Route::middleware(['auth', 'role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});
