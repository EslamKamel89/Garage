<?php

namespace Database\Seeders;

use App\Models\Labour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboursSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $labours = [
            ['name_ar' => 'صيانة دورية', 'name_en' => 'Routine Maintenance', 'base_fee' => 100],
            ['name_ar' => 'تغيير زيت المحرك', 'name_en' => 'Engine Oil Change', 'base_fee' => 75],
            ['name_ar' => 'فحص نظام الفرامل', 'name_en' => 'Brake System Inspection', 'base_fee' => 50],
            ['name_ar' => 'استبدال فلتر الهواء', 'name_en' => 'Air Filter Replacement', 'base_fee' => 30],
            ['name_ar' => 'استبدال فلتر الزيت', 'name_en' => 'Oil Filter Replacement', 'base_fee' => 25],
            ['name_ar' => 'تعبئة غاز المكيف', 'name_en' => 'AC Gas Refill', 'base_fee' => 120],
            ['name_ar' => 'تشخيص إلكتروني', 'name_en' => 'Electronic Diagnostic', 'base_fee' => 80],
            ['name_ar' => 'استبدال شمعات الإشعال', 'name_en' => 'Spark Plug Replacement', 'base_fee' => 90],
            ['name_ar' => 'غسيل المحرك', 'name_en' => 'Engine Wash', 'base_fee' => 40],
            ['name_ar' => 'تعديل زوايا العجلات', 'name_en' => 'Wheel Alignment', 'base_fee' => 150],
            ['name_ar' => 'تبديل بطانات الفرامل', 'name_en' => 'Brake Pads Replacement', 'base_fee' => 200],
            ['name_ar' => 'شحن البطارية', 'name_en' => 'Battery Charging', 'base_fee' => 60],
        ];
        foreach ($labours as $labour) {
            Labour::create($labour);
        }
    }
}
