<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Skin',
                'slug' => 'skin',
                'description' => 'Kosmetik untuk karakter atau senjata.',
                'image' => '/images/categories/skin.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Weapon',
                'slug' => 'weapon',
                'description' => 'Skin senjata dalam game.',
                'image' => '/images/categories/weapon.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Bundle',
                'slug' => 'bundle',
                'description' => 'Paket item dengan harga lebih murah.',
                'image' => '/images/categories/bundle.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Voucher',
                'slug' => 'voucher',
                'description' => 'Voucher game atau langganan.',
                'image' => '/images/categories/voucher.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Currency',
                'slug' => 'currency',
                'description' => 'Mata uang virtual dalam game.',
                'image' => '/images/categories/currency.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Item',
                'slug' => 'item',
                'description' => 'Item atau perlengkapan lainnya.',
                'image' => '/images/categories/item.svg',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
