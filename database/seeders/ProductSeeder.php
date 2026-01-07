<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create Electronics category
        $electronics = Category::firstOrCreate(
            ['slug' => 'electronics'],
            [
                'name' => 'Electronics',
                'description' => 'Latest electronic gadgets and devices',
            ]
        );

        // Get or create Books category
        $books = Category::firstOrCreate(
            ['slug' => 'books'],
            [
                'name' => 'Books',
                'description' => 'Educational and entertainment books',
            ]
        );

        // Create 10 Electronics Products
        $electronicProducts = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life',
                'price' => 450000,
                'stock' => 45,
            ],
            [
                'name' => 'USB-C Fast Charger 65W',
                'slug' => 'usb-c-fast-charger-65w',
                'description' => 'Fast charging adapter compatible with all USB-C devices, compact design',
                'price' => 280000,
                'stock' => 60,
            ],
            [
                'name' => '4K Webcam USB',
                'slug' => '4k-webcam-usb',
                'description' => '4K resolution webcam with built-in microphone for streaming and video calls',
                'price' => 520000,
                'stock' => 25,
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'slug' => 'mechanical-keyboard-rgb',
                'description' => 'Gaming mechanical keyboard with RGB lighting and customizable switches',
                'price' => 750000,
                'stock' => 35,
            ],
            [
                'name' => 'Wireless Mouse Ergonomic',
                'slug' => 'wireless-mouse-ergonomic',
                'description' => 'Ergonomic wireless mouse with adjustable DPI and long battery life',
                'price' => 185000,
                'stock' => 70,
            ],
            [
                'name' => 'Portable SSD 1TB USB-C',
                'slug' => 'portable-ssd-1tb',
                'description' => '1TB portable SSD with high-speed USB-C connection, rugged design',
                'price' => 950000,
                'stock' => 20,
            ],
            [
                'name' => 'Smart LED Light Bulbs Set',
                'slug' => 'smart-led-light-bulbs',
                'description' => 'Set of 3 smart LED bulbs with WiFi control and color adjustment',
                'price' => 320000,
                'stock' => 40,
            ],
            [
                'name' => 'Tablet Stand Aluminum',
                'slug' => 'tablet-stand-aluminum',
                'description' => 'Premium aluminum stand compatible with all tablets and large phones',
                'price' => 145000,
                'stock' => 80,
            ],
            [
                'name' => 'USB Hub 7-Port',
                'slug' => 'usb-hub-7-port',
                'description' => 'High-speed USB 3.0 hub with 7 ports and power adapter',
                'price' => 320000,
                'stock' => 30,
            ],
            [
                'name' => 'Monitor 27 Inch 4K',
                'slug' => 'monitor-27-inch-4k',
                'description' => 'Professional 4K monitor with USB-C and HDR support',
                'price' => 2800000,
                'stock' => 12,
            ],
        ];

        foreach ($electronicProducts as $product) {
            Product::firstOrCreate(
                ['slug' => $product['slug']],
                array_merge($product, ['category_id' => $electronics->id])
            );
        }

        // Create 10 Books Products
        $bookProducts = [
            [
                'name' => 'Laravel Practical Guide 2024',
                'slug' => 'laravel-practical-guide-2024',
                'description' => 'Complete guide to building modern web applications with Laravel framework',
                'price' => 285000,
                'stock' => 50,
            ],
            [
                'name' => 'PHP Advanced Programming',
                'slug' => 'php-advanced-programming',
                'description' => 'Master advanced PHP concepts and design patterns for professional development',
                'price' => 320000,
                'stock' => 40,
            ],
            [
                'name' => 'Clean Code Principles',
                'slug' => 'clean-code-principles',
                'description' => 'Learn how to write maintainable and efficient code that scales well',
                'price' => 275000,
                'stock' => 55,
            ],
            [
                'name' => 'Database Design Mastery',
                'slug' => 'database-design-mastery',
                'description' => 'In-depth guide to designing efficient database schemas and optimization',
                'price' => 310000,
                'stock' => 35,
            ],
            [
                'name' => 'Web Security Essentials',
                'slug' => 'web-security-essentials',
                'description' => 'Complete guide to securing web applications from common vulnerabilities',
                'price' => 295000,
                'stock' => 45,
            ],
            [
                'name' => 'JavaScript Modern Techniques',
                'slug' => 'javascript-modern-techniques',
                'description' => 'Learn modern JavaScript ES6+ features and asynchronous programming',
                'price' => 265000,
                'stock' => 60,
            ],
            [
                'name' => 'REST API Design Best Practices',
                'slug' => 'rest-api-design-best-practices',
                'description' => 'Guide to designing scalable and maintainable REST APIs',
                'price' => 280000,
                'stock' => 38,
            ],
            [
                'name' => 'Unit Testing for Developers',
                'slug' => 'unit-testing-for-developers',
                'description' => 'Master unit testing, TDD, and continuous integration practices',
                'price' => 250000,
                'stock' => 52,
            ],
            [
                'name' => 'DevOps and Docker Guide',
                'slug' => 'devops-and-docker-guide',
                'description' => 'Complete guide to containerization with Docker and deployment automation',
                'price' => 315000,
                'stock' => 30,
            ],
            [
                'name' => 'Microservices Architecture',
                'slug' => 'microservices-architecture',
                'description' => 'Learn to design, build and deploy microservices-based applications',
                'price' => 340000,
                'stock' => 28,
            ],
        ];

        foreach ($bookProducts as $product) {
            Product::firstOrCreate(
                ['slug' => $product['slug']],
                array_merge($product, ['category_id' => $books->id])
            );
        }
    }
}
