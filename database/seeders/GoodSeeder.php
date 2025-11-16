<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Good;
use App\Models\Category;
use Carbon\Carbon;

class GoodSeeder extends Seeder
{
    public function run(): void
    {
        $vendorId = 1; // change this depending on your seed data

        $goodsData = [
            'fruits' => [
                [
                    'name' => 'Fresh Apples',
                    'description' => 'Crisp red apples from the local farm.',
                    'price' => 3.50,
                    'quantity' => 120,
                ],
                [
                    'name' => 'Bananas',
                    'description' => 'Organic bananas, ripe and sweet.',
                    'price' => 2.20,
                    'quantity' => 80,
                ],
            ],

            'hardware' => [
                [
                    'name' => 'Steel Hammer',
                    'description' => 'High-quality steel hammer for construction.',
                    'price' => 15.99,
                    'quantity' => 25,
                ],
                [
                    'name' => 'Screwdriver Set',
                    'description' => 'Multi-size magnetic screwdrivers.',
                    'price' => 9.99,
                    'quantity' => 40,
                ],
            ],

            'clothes' => [
                [
                    'name' => 'Cotton T-Shirt',
                    'description' => 'Soft cotton t-shirt in assorted colors.',
                    'price' => 12.00,
                    'quantity' => 60,
                ],
                [
                    'name' => 'Denim Jeans',
                    'description' => 'Blue slim-fit denim jeans.',
                    'price' => 35.00,
                    'quantity' => 35,
                ],
            ],

            'books' => [
                [
                    'name' => 'Laravel For Beginners',
                    'description' => 'Introductory guide for mastering Laravel.',
                    'price' => 18.50,
                    'quantity' => 50,
                ],
                [
                    'name' => 'Advanced PHP Concepts',
                    'description' => 'Deep dive into modern PHP development.',
                    'price' => 22.00,
                    'quantity' => 30,
                ],
            ],

            'electronics' => [
                [
                    'name' => 'Wireless Earbuds',
                    'description' => 'Noise-cancelling Bluetooth earbuds.',
                    'price' => 49.99,
                    'quantity' => 70,
                ],
                [
                    'name' => 'Portable Charger',
                    'description' => '10,000mAh fast-charging power bank.',
                    'price' => 25.99,
                    'quantity' => 90,
                ],
            ],

            'furniture' => [
                [
                    'name' => 'Office Chair',
                    'description' => 'Ergonomic office chair with lumbar support.',
                    'price' => 89.99,
                    'quantity' => 15,
                ],
                [
                    'name' => 'Wooden Coffee Table',
                    'description' => 'Solid wood coffee table with natural finish.',
                    'price' => 120.00,
                    'quantity' => 10,
                ],
            ],
        ];

        foreach ($goodsData as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();

            foreach ($items as $item) {
                Good::create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'recived_date' => Carbon::now()->subDays(rand(1, 30)),
                    'category_id' => $category->id,
                    'vendor_id' => $vendorId,
                ]);
            }
        }
    }
}
