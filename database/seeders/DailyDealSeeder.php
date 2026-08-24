<?php

namespace Database\Seeders;

use App\Models\DailyDeal;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DailyDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::active()->take(4)->get();
        if ($products->isEmpty()) {
            return;
        }

        $dealsData = [
            [
                'discount_percent' => 30,
                'title' => 'Flash Sale Giờ Vàng',
                'slot_name' => 'Cả ngày (00:00 - 23:59)',
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
                'quantity_limit' => 50,
                'sold_count' => 18,
            ],
            [
                'discount_percent' => 40,
                'title' => 'Ưu Đãi Sốc Trong Ngày',
                'slot_name' => 'Cả ngày (00:00 - 23:59)',
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
                'quantity_limit' => 30,
                'sold_count' => 12,
            ],
            [
                'discount_percent' => 25,
                'title' => 'Deal Hè Cực Cháy',
                'slot_name' => 'Cả ngày (00:00 - 23:59)',
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
                'quantity_limit' => 40,
                'sold_count' => 22,
            ],
            [
                'discount_percent' => 35,
                'title' => 'Siêu Ưu Đãi Ngày Mới',
                'slot_name' => 'Cả ngày (00:00 - 23:59)',
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
                'quantity_limit' => 25,
                'sold_count' => 9,
            ],
        ];

        foreach ($products as $index => $product) {
            $data = $dealsData[$index % count($dealsData)];
            $dealPrice = max(0, (int) round($product->price * (1 - ($data['discount_percent'] / 100))));

            DailyDeal::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'title' => $data['title'],
                    'discount_percent' => $data['discount_percent'],
                    'deal_price' => $dealPrice,
                    'deal_date' => now()->toDateString(),
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'slot_name' => $data['slot_name'],
                    'quantity_limit' => $data['quantity_limit'],
                    'sold_count' => $data['sold_count'],
                    'is_active' => true,
                ]
            );
        }
    }
}
