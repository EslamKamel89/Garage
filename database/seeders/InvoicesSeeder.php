<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InvoicesSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $clients = Client::limit(3)->get();
        $products = Product::all();
        foreach ($clients as $client) {
            $invoice1 = Invoice::create([
                'client_id' => $client->id,
                'user_id' => 1,
                'status' => InvoiceStatus::Unpaid,
                'labor_info' => [
                    'items' => [
                        ['name' => 'تغيير زيت المحرك', 'fee' => 75],
                        ['name' => 'فحص نظام الفرامل', 'fee' => 50],
                    ]
                ],
                'calculated_total' => 225,
                'actual_total' => 225,
                'actual_paid_amount' => 0,
                'notes' => 'العميل سيأتي بعد أسبوع لاستلام السيارة',
            ]);
            InvoiceItem::create([
                'invoice_id' => $invoice1->id,
                'product_id' => $products->firstWhere('code', 'OIL-001')->id,
                'quantity' => 1,
                'unit_price' => 75.00,
                'total_price' => 75.00,
            ]);
            InvoiceItem::create([
                'invoice_id' => $invoice1->id,
                'product_id' => $products->firstWhere('code', 'FIL-001')->id,
                'quantity' => 1,
                'unit_price' => 30.00,
                'total_price' => 30.00,
            ]);
            $invoice2 = Invoice::create([
                'client_id' => $client->id,
                'user_id' => 1,
                'status' => InvoiceStatus::Paid,
                'labor_info' => [
                    'items' => [
                        ['name' => 'صيانة دورية', 'fee' => 100],
                    ]
                ],
                'calculated_total' => 145,
                'actual_total' => 140, // 5 ريال خصم
                'actual_paid_amount' => 140,
                'notes' => 'تم الدفع نقداً',
            ]);
            InvoiceItem::create([
                'invoice_id' => $invoice2->id,
                'product_id' => $products->firstWhere('code', 'FIL-002')->id,
                'quantity' => 1,
                'unit_price' => 45.00,
                'total_price' => 45.00,
            ]);
        };
    }
}
