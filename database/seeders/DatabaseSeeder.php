<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun untuk 3 Role
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@toko.com',
            'password' => bcrypt('password'), // password: password
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@toko.com',
            'password' => bcrypt('password'),
            'role' => 'staff'
        ]);

        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'user@toko.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        // 2. Buat Dummy Kategori
        $cat1 = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $cat2 = Category::create(['name' => 'Pakaian', 'slug' => 'pakaian']);

        // 3. Buat Dummy Produk
        Product::create([
            'category_id' => $cat1->id,
            'name' => 'Laptop Gaming',
            'slug' => 'laptop-gaming',
            'description' => 'Laptop spek tinggi untuk gaming.',
            'price' => 15000000,
            'stock' => 10,
            'image' => null
        ]);

        Product::create([
            'category_id' => $cat2->id,
            'name' => 'Kaos Polos',
            'slug' => 'kaos-polos',
            'description' => 'Bahan katun nyaman.',
            'price' => 50000,
            'stock' => 100,
            'image' => null
        ]);
    }
}
