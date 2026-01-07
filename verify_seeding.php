<?php

// Quick verification script
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

echo "\n====== DATABASE VERIFICATION REPORT ======\n\n";

// Check categories
echo "📦 CATEGORIES:\n";
Category::withCount('products')->get()->each(function($category) {
    echo "  • {$category->name}: {$category->products_count} products\n";
});

// Check total products
echo "\n📚 PRODUCTS:\n";
echo "  • Total Products: " . Product::count() . "\n";
echo "  • Electronics: " . Product::whereHas('category', fn($q) => $q->where('slug', 'electronics'))->count() . "\n";
echo "  • Books: " . Product::whereHas('category', fn($q) => $q->where('slug', 'books'))->count() . "\n";

// Check customers
echo "\n👥 CUSTOMERS:\n";
$customers = User::where('role', 'customer')->get();
echo "  • Total Customers: " . $customers->count() . "\n";

// Check orders
echo "\n📋 ORDERS:\n";
echo "  • Total Orders: " . Order::count() . "\n";
$orders = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
echo "  • Orders by Status:\n";
foreach ($orders as $order) {
    echo "    - {$order->status}: {$order->count}\n";
}

// Check customer orders
echo "\n🛒 ORDERS PER CUSTOMER:\n";
foreach ($customers as $customer) {
    $orderCount = $customer->orders()->count();
    echo "  • {$customer->name}: {$orderCount} orders\n";
}

echo "\n✅ DATABASE SEEDING COMPLETED SUCCESSFULLY!\n\n";
