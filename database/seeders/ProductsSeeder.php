<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $products = [
            [
                'category_id' => 1, // زيت
                'code' => 'OIL-001',
                'name' => 'زيت محرك 10W-40',
                'description' => 'زيت محركات عالي الجودة، مناسب لمعظم سيارات نيسان',
                'buy_price' => 45.00,
                'sell_price' => 75.00,
                'quantity' => 25,
                'min_stock_quantity' => 10,
            ],
            [
                'category_id' => 2, // فلتر
                'code' => 'FIL-001',
                'name' => 'فلتر زيت نيسان',
                'description' => 'فلتر زيت أصلي لسيارات نيسان صني وتيدا',
                'buy_price' => 18.00,
                'sell_price' => 30.00,
                'quantity' => 40,
                'min_stock_quantity' => 15,
            ],
            [
                'category_id' => 2,
                'code' => 'FIL-002',
                'name' => 'فلتر هواء نيسان',
                'description' => 'فلتر هواء عالي الأداء',
                'buy_price' => 25.00,
                'sell_price' => 45.00,
                'quantity' => 20,
                'min_stock_quantity' => 8,
            ],
            [
                'category_id' => 3, // فرامل
                'code' => 'BRA-001',
                'name' => 'بطانات فرامل أمامية',
                'description' => 'بطانات فرامل أمامية لسيارات نيسان 2015–2020',
                'buy_price' => 80.00,
                'sell_price' => 160.00,
                'quantity' => 12,
                'min_stock_quantity' => 5,
            ],
            [
                'category_id' => 6, // بطارية
                'code' => 'BAT-001',
                'name' => 'بطارية 60 أمبير',
                'description' => 'بطارية جديدة، ضمان 24 شهر',
                'buy_price' => 180.00,
                'sell_price' => 280.00,
                'quantity' => 8,
                'min_stock_quantity' => 3,
            ],
            [
                'category_id' => 7, // مكيف
                'code' => 'AC-001',
                'name' => 'مروحة مكيف',
                'description' => 'مروحة نظام التبريد',
                'buy_price' => 120.00,
                'sell_price' => 200.00,
                'quantity' => 6,
                'min_stock_quantity' => 2,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
