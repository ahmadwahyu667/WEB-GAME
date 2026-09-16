<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = [
            [
                'title' => 'Diskon Spesial Akhir Pekan',
                'description' => 'Dapatkan diskon 20% untuk semua item di akhir pekan ini!',
                'image' => '/images/promos/promo1.jpg',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_purchase' => 100000,
                'max_discount' => 50000,
                'usage_limit' => 100,
                'usage_count' => 0,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'status' => 'active',
            ],
            [
                'title' => 'Flash Sale Item Langka',
                'description' => 'Potongan langsung Rp50.000 untuk pembelian item langka.',
                'image' => '/images/promos/promo2.jpg',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_purchase' => 200000,
                'max_discount' => 50000,
                'usage_limit' => 50,
                'usage_count' => 0,
                'starts_at' => now(),
                'ends_at' => now()->addDays(7),
                'status' => 'active',
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}
