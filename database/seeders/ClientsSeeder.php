<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $clients = [
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'أحمد محمد',
                'mobile' => '0591234567',
                'email' => 'ahmed@example.com',
                'address' => 'شارع  فهد، ',
                'notes' => 'عميل موثوق، يأتي للصيانة كل 6 أشهر',
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'خالد عبد الله',
                'mobile' => '0592345678',
                'email' => 'khalid@example.com',
                'address' => 'حي النخيل',
                'notes' => 'يملك نيسان صني 2019',
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'فهد سعيد',
                'mobile' => '0593456789',
                'email' => 'fahd@example.com',
                'address' => ' حي العليا',
                'notes' => 'يحتاج خدمة سريعة، لا يحب الانتظار',
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'سامي عمر',
                'mobile' => '0594567890',
                'email' => 'sami@example.com',
                'address' => '  شارع إبراهيم الخليل',
                'notes' => 'عميل جديد، تم التواصل عبر واتساب',
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'ناصر منصور',
                'mobile' => '0595678901',
                'email' => 'nasser@example.com',
                'address' => '  حي الأندلس',
                'notes' => 'يملك سيارتين نيسان',
            ],
        ];
        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
