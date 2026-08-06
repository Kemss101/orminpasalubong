<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasalubongProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = Category::query()
            ->pluck('id_category', 'name')
            ->toArray();

        $products = [
            [
                'category' => 'Delicacies',
                'sku_code' => 'PSLB-BAN-001',
                'name' => 'Sweetened Banana Chips',
                'description' => 'Crunchy and sweet banana chips.',
                'price' => 85.00,
                'stock_quantity' => 50,
                'low_stock_threshold' => 10,
                'image_path' => 'images/products/banana-chips.jpg',
            ],
            [
                'category' => 'Sweets',
                'sku_code' => 'PSLB-COC-002',
                'name' => 'Premium Coco Jam',
                'description' => 'Rich coconut jam in a glass jar.',
                'price' => 120.00,
                'stock_quantity' => 35,
                'low_stock_threshold' => 10,
                'image_path' => 'images/products/coco-jam.jpg',
            ],
            [
                'category' => 'Sweets',
                'sku_code' => 'PSLB-UBE-003',
                'name' => 'Mindoro Ube Halaya',
                'description' => 'Traditional purple yam sweet spread.',
                'price' => 150.00,
                'stock_quantity' => 25,
                'low_stock_threshold' => 8,
                'image_path' => 'images/products/ube-halaya.jpg',
            ],
            [
                'category' => 'Delicacies',
                'sku_code' => 'PSLB-SUM-004',
                'name' => 'Suman sa Lihiya',
                'description' => 'Sticky rice delicacy wrapped and ready to serve.',
                'price' => 50.00,
                'stock_quantity' => 60,
                'low_stock_threshold' => 12,
                'image_path' => 'images/products/suman.jpg',
            ],
            [
                'category' => 'Sweets',
                'sku_code' => 'PSLB-SWE-014',
                'name' => 'Yema Candy',
                'description' => 'Creamy milk candy that melts in your mouth.',
                'price' => 65.00,
                'stock_quantity' => 40,
                'low_stock_threshold' => 10,
                'image_path' => 'images/products/yema-candy.jpg',
            ],
            [
                'category' => 'Sweets',
                'sku_code' => 'PSLB-SWE-015',
                'name' => 'Polvoron',
                'description' => 'Classic crumbly milk shortbread snack.',
                'price' => 95.00,
                'stock_quantity' => 36,
                'low_stock_threshold' => 10,
                'image_path' => 'images/products/polvoron.jpg',
            ],
            [
                'category' => 'Sweets',
                'sku_code' => 'PSLB-SWE-016',
                'name' => 'Pastillas de Leche',
                'description' => 'Soft and milky candy wrapped in colorful paper.',
                'price' => 75.00,
                'stock_quantity' => 32,
                'low_stock_threshold' => 9,
                'image_path' => 'images/products/pastillas.jpg',
            ],
            [
                'category' => 'Delicacies',
                'sku_code' => 'PSLB-DEL-005',
                'name' => 'Cassava Cake',
                'description' => 'Rich cassava dessert with creamy topping.',
                'price' => 140.00,
                'stock_quantity' => 28,
                'low_stock_threshold' => 8,
                'image_path' => 'images/products/cassava-cake.jpg',
            ],
            [
                'category' => 'Delicacies',
                'sku_code' => 'PSLB-DEL-006',
                'name' => 'Bibingka',
                'description' => 'Traditional rice cake served warm and fragrant.',
                'price' => 120.00,
                'stock_quantity' => 30,
                'low_stock_threshold' => 8,
                'image_path' => 'images/products/bibingka.jpg',
            ],
            [
                'category' => 'Delicacies',
                'sku_code' => 'PSLB-DEL-007',
                'name' => 'Buko Pie',
                'description' => 'Buttery crust pie with sweet young coconut filling.',
                'price' => 230.00,
                'stock_quantity' => 16,
                'low_stock_threshold' => 5,
                'image_path' => 'images/products/buko-pie.jpg',
            ],
            [
                'category' => 'Souvenirs',
                'sku_code' => 'PSLB-SOU-008',
                'name' => 'Souvenir Keychain',
                'description' => 'Pocket souvenir keepsake for family and friends.',
                'price' => 60.00,
                'stock_quantity' => 42,
                'low_stock_threshold' => 10,
                'image_path' => 'images/products/souvenir-keychain.jpg',
            ],
            [
                'category' => 'Souvenirs',
                'sku_code' => 'PSLB-SOU-009',
                'name' => 'Ref Magnet',
                'description' => 'Collectible fridge magnet with local travel vibe.',
                'price' => 55.00,
                'stock_quantity' => 45,
                'low_stock_threshold' => 12,
                'image_path' => 'images/products/ref-magnet.jpg',
            ],
            [
                'category' => 'Souvenirs',
                'sku_code' => 'PSLB-SOU-010',
                'name' => 'Handwoven Bag',
                'description' => 'Lightweight woven bag perfect for pasalubong gifts.',
                'price' => 320.00,
                'stock_quantity' => 14,
                'low_stock_threshold' => 4,
                'image_path' => 'images/products/woven-bag.jpg',
            ],
            [
                'category' => 'Wines & Beverages',
                'sku_code' => 'PSLB-WNB-011',
                'name' => 'Calamansi Juice',
                'description' => 'Refreshing citrus drink, sweet and tangy.',
                'price' => 45.00,
                'stock_quantity' => 55,
                'low_stock_threshold' => 15,
                'image_path' => 'images/products/calamansi-juice.jpg',
            ],
            [
                'category' => 'Wines & Beverages',
                'sku_code' => 'PSLB-WNB-012',
                'name' => 'Coconut Wine',
                'description' => 'Traditional coconut-based local wine beverage.',
                'price' => 180.00,
                'stock_quantity' => 20,
                'low_stock_threshold' => 6,
                'image_path' => 'images/products/coconut-wine.jpg',
            ],
            [
                'category' => 'Wines & Beverages',
                'sku_code' => 'PSLB-WNB-013',
                'name' => 'Kapeng Barako',
                'description' => 'Strong local coffee with bold Batangas aroma.',
                'price' => 160.00,
                'stock_quantity' => 22,
                'low_stock_threshold' => 7,
                'image_path' => 'images/products/kapeng-barako.jpg',
            ],
        ];

        foreach ($products as $product) {
            $categoryName = $product['category'] ?? null;
            $categoryId = $categoryName && isset($categoryMap[$categoryName]) ? $categoryMap[$categoryName] : null;
            $payload = $product;
            unset($payload['category']);

            DB::table('products')->updateOrInsert(
                ['sku_code' => $product['sku_code']],
                array_merge($payload, [
                    'category_id' => $categoryId,
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}
