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
                'chassis_nu' => 'JN1BJ0HRXFM123456',
                'plate_nu' => 'ج د 1234',
                'odometer' => 45678
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'خالد عبد الله',
                'mobile' => '0592345678',
                'email' => 'khalid@example.com',
                'address' => 'حي النخيل',
                'notes' => 'يملك نيسان صني 2019',
                'chassis_nu' => 'ZFA22300005567890',
                'plate_nu' => 'ه و 5678',
                'odometer' => 89234
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'فهد سعيد',
                'mobile' => '0593456789',
                'email' => 'fahd@example.com',
                'address' => ' حي العليا',
                'notes' => 'يحتاج خدمة سريعة، لا يحب الانتظار',
                'chassis_nu' => 'WAUZZZ8K9FA123789',
                'plate_nu' => 'أ ب 9012',
                'odometer' => 123456
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'سامي عمر',
                'mobile' => '0594567890',
                'email' => 'sami@example.com',
                'address' => '  شارع إبراهيم الخليل',
                'notes' => 'عميل جديد، تم التواصل عبر واتساب',
                'chassis_nu' => 'KMHTC6AE2FU345678',
                'plate_nu' => 'ر س 3456',
                'odometer' => 23456
            ],
            [
                'car_model_id' => fake()->numberBetween(1, 8),
                'name' => 'ناصر منصور',
                'mobile' => '0595678901',
                'email' => 'nasser@example.com',
                'address' => '  حي الأندلس',
                'notes' => 'يملك سيارتين نيسان',
                'chassis_nu' => '1HGCM82633A123456',
                'plate_nu' => 'م ن 7890',
                'odometer' => 67890
            ],
        ];
        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
