<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $books = Category::create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'Educational and entertainment books',
        ]);

        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Latest electronic gadgets and devices',
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Fashion and casual wear',
        ]);

        $home = Category::create([
            'name' => 'Home & Garden',
            'slug' => 'home-garden',
            'description' => 'Home decoration and garden tools',
        ]);

        // Create sample products for Books
        Product::create([
            'category_id' => $books->id,
            'name' => 'Laravel Guide',
            'slug' => 'laravel-guide',
            'description' => 'Complete guide to Laravel framework for beginners and advanced developers',
            'price' => 250000,
            'stock' => 30,
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'PHP Mastery',
            'slug' => 'php-mastery',
            'description' => 'Advanced PHP programming techniques and best practices',
            'price' => 280000,
            'stock' => 25,
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'Web Design Essentials',
            'slug' => 'web-design-essentials',
            'description' => 'Learn responsive web design and modern UI/UX principles',
            'price' => 200000,
            'stock' => 40,
        ]);

        // Create sample products for Electronics
        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Wireless Mouse',
            'slug' => 'wireless-mouse',
            'description' => 'Professional ergonomic wireless mouse with long battery life',
            'price' => 150000,
            'stock' => 50,
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'USB-C Hub',
            'slug' => 'usb-c-hub',
            'description' => 'Multi-port USB-C hub with HDMI, USB 3.0 and charging',
            'price' => 350000,
            'stock' => 20,
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'LED Monitor 27 inch',
            'slug' => 'led-monitor-27',
            'description' => '4K LED Monitor with HDR support and USB-C',
            'price' => 2500000,
            'stock' => 15,
        ]);

        // Create sample products for Clothing
        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Cotton T-Shirt',
            'slug' => 'cotton-t-shirt',
            'description' => 'Comfortable 100% cotton t-shirt in various colors',
            'price' => 75000,
            'stock' => 100,
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Denim Jeans',
            'slug' => 'denim-jeans',
            'description' => 'Classic denim jeans with modern fit',
            'price' => 250000,
            'stock' => 60,
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Sports Shoes',
            'slug' => 'sports-shoes',
            'description' => 'Professional running shoes with advanced cushioning',
            'price' => 500000,
            'stock' => 35,
        ]);

        // Create sample products for Home & Garden
        Product::create([
            'category_id' => $home->id,
            'name' => 'Desk Lamp',
            'slug' => 'desk-lamp',
            'description' => 'LED desk lamp with adjustable brightness and color temperature',
            'price' => 200000,
            'stock' => 45,
        ]);

        Product::create([
            'category_id' => $home->id,
            'name' => 'Plant Pot',
            'slug' => 'plant-pot',
            'description' => 'Ceramic plant pot suitable for indoor plants',
            'price' => 85000,
            'stock' => 70,
        ]);

        Product::create([
            'category_id' => $home->id,
            'name' => 'Curtain Set',
            'slug' => 'curtain-set',
            'description' => 'Elegant curtain set with modern design',
            'price' => 350000,
            'stock' => 25,
        ]);
    }
}
