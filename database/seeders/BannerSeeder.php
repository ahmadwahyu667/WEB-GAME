<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'DISKON HINGGA 50%',
                'subtitle' => 'Item game pilihan dengan harga spesial',
                'image' => '/images/banners/banner1.svg',
                'cta_text' => 'Belanja Sekarang',
                'cta_url' => '/products',
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'title' => 'ITEM GAME TERBARU',
                'subtitle' => 'Koleksi skin dan item terbaru',
                'image' => '/images/banners/banner2.svg',
                'cta_text' => 'Lihat Koleksi',
                'cta_url' => '/products?sort=terbaru',
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'PROMO SPESIAL QRIS',
                'subtitle' => 'Bayar dengan QRIS dan dapatkan harga spesial',
                'image' => '/images/banners/banner3.svg',
                'cta_text' => 'Lihat Promo',
                'cta_url' => '/promo',
                'sort_order' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
