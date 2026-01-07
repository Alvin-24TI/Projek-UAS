<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Create customer users for transactions
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'password' => bcrypt('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'password' => bcrypt('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Ahmad Ridho',
                'email' => 'ahmad.ridho@email.com',
                'password' => bcrypt('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@email.com',
                'password' => bcrypt('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Roni Hermawan',
                'email' => 'roni.hermawan@email.com',
                'password' => bcrypt('password123'),
                'role' => 'customer',
            ],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $user = User::firstOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );
            $createdCustomers[] = $user;
        }

        // Get all products
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        // Create sample transactions for each customer
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentMethods = ['transfer', 'cod', 'card'];
        $shippingCities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Yogyakarta'];
        $phones = ['081234567890', '082345678901', '083456789012', '084567890123', '085678901234'];

        foreach ($createdCustomers as $index => $customer) {
            // Each customer gets 3-5 orders
            $orderCount = rand(3, 5);

            for ($i = 0; $i < $orderCount; $i++) {
                $orderNumber = 'ORD-' . now()->format('YmdHis') . '-' . str_pad($customer->id . $i, 4, '0', STR_PAD_LEFT);

                // Generate 2-4 items per order
                $itemCount = rand(2, 4);
                $selectedProducts = $products->random($itemCount);
                $totalAmount = 0;
                $orderItems = [];

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $totalAmount += $price * $quantity;
                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                }

                // Create order
                $order = Order::create([
                    'user_id' => $customer->id,
                    'order_number' => $orderNumber,
                    'status' => $statuses[array_rand($statuses)],
                    'total_amount' => $totalAmount,
                    'shipping_address' => 'Jalan ' . $this->getRandomStreet() . ' No. ' . rand(1, 999),
                    'shipping_phone' => $phones[$index] ?? '081234567890',
                    'shipping_city' => $shippingCities[array_rand($shippingCities)],
                    'shipping_province' => $this->getRandomProvince(),
                    'shipping_zip' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                ]);

                // Create order items
                foreach ($orderItems as $itemData) {
                    OrderItem::create(array_merge($itemData, ['order_id' => $order->id]));
                }
            }
        }

        $this->command->info('Transaction seeder completed successfully!');
        $this->command->info('Created ' . count($createdCustomers) . ' customers with multiple orders.');
    }

    private function getRandomStreet(): string
    {
        $streets = [
            'Merdeka',
            'Ahmad Yani',
            'Sudirman',
            'Gatot Subroto',
            'Jend. Soedirman',
            'Imam Bonjol',
            'Diponegoro',
            'Gajah Mada',
            'Kartini',
            'Hayam Wuruk',
            'Sultan Agung',
            'Teuku Umar',
            'Andong',
            'Soekarno',
            'Palmerah',
        ];
        return $streets[array_rand($streets)];
    }

    private function getRandomProvince(): string
    {
        $provinces = [
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'Jawa Timur',
            'Yogyakarta',
            'Sumatra Utara',
            'Sumatra Selatan',
            'Kalimantan Timur',
            'Sulawesi Selatan',
            'Bali',
        ];
        return $provinces[array_rand($provinces)];
    }
}
