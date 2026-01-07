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

        // Create sample products
        Product::create([
            'category_id' => $books->id,
            'name' => 'Laravel Guide',
            'slug' => 'laravel-guide',
            'description' => 'Complete guide to Laravel framework',
            'price' => 250000,
            'stock' => 30,
        ]);
    }
}
