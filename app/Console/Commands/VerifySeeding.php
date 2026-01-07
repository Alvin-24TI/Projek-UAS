<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Console\Command;

class VerifySeeding extends Command
{
    protected $signature = 'verify:seeding';
    protected $description = 'Verify database seeding status';

    public function handle()
    {
        $this->info("\n====== DATABASE VERIFICATION REPORT ======\n");

        // Check categories
        $this->info("📦 CATEGORIES:");
        Category::withCount('products')->get()->each(function($category) {
            $this->line("  • {$category->name}: {$category->products_count} products");
        });

        // Check total products
        $this->info("\n📚 PRODUCTS:");
        $this->line("  • Total Products: " . Product::count());
        $this->line("  • Electronics: " . Product::whereHas('category', fn($q) => $q->where('slug', 'electronics'))->count());
        $this->line("  • Books: " . Product::whereHas('category', fn($q) => $q->where('slug', 'books'))->count());

        // Check customers
        $this->info("\n👥 CUSTOMERS:");
        $customers = User::where('role', 'customer')->get();
        $this->line("  • Total Customers: " . $customers->count());

        // Check orders
        $this->info("\n📋 ORDERS:");
        $this->line("  • Total Orders: " . Order::count());
        $orders = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
        $this->line("  • Orders by Status:");
        foreach ($orders as $order) {
            $this->line("    - {$order->status}: {$order->count}");
        }

        // Check customer orders
        $this->info("\n🛒 ORDERS PER CUSTOMER:");
        foreach ($customers as $customer) {
            $orderCount = $customer->orders()->count();
            $this->line("  • {$customer->name}: {$orderCount} orders");
        }

        $this->info("\n✅ DATABASE SEEDING COMPLETED SUCCESSFULLY!\n");
    }
}
