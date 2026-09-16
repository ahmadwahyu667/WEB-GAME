<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dota2 = Game::where('slug', 'dota-2')->first();
        $cs2 = Game::where('slug', 'counter-strike-2')->first();
        $valorant = Game::where('slug', 'valorant')->first();
        $mlbb = Game::where('slug', 'mobile-legends')->first();
        $genshin = Game::where('slug', 'genshin-impact')->first();

        $skin = Category::where('slug', 'skin')->first();
        $weapon = Category::where('slug', 'weapon')->first();
        $bundle = Category::where('slug', 'bundle')->first();
        $voucher = Category::where('slug', 'voucher')->first();
        $currency = Category::where('slug', 'currency')->first();

        $products = [
            // Dota 2
            [
                'game_id' => $dota2->id,
                'category_id' => $skin->id,
                'name' => 'Dragon Blade',
                'slug' => 'dragon-blade',
                'description' => 'Skin pedang legendaris untuk hero Dragon Knight.',
                'price' => 79000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $dota2->id,
                'category_id' => $skin->id,
                'name' => 'Shadow Fiend Arcana',
                'slug' => 'shadow-fiend-arcana',
                'description' => 'Arcana eksklusif untuk Shadow Fiend dengan efek visual menakjubkan.',
                'price' => 149000,
                'discount_price' => 119000,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $dota2->id,
                'category_id' => $bundle->id,
                'name' => 'Phantom Assassin Bundle',
                'slug' => 'phantom-assassin-bundle',
                'description' => 'Paket lengkap set Phantom Assassin beserta senjata.',
                'price' => 299000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],

            // CS2
            [
                'game_id' => $cs2->id,
                'category_id' => $weapon->id,
                'name' => 'AK-47 Neon Rider',
                'slug' => 'ak-47-neon-rider',
                'description' => 'Skin senjata AK-47 dengan tema cyberpunk neon.',
                'price' => 189000,
                'discount_price' => 159000,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $cs2->id,
                'category_id' => $weapon->id,
                'name' => 'AWP Dragon Lore',
                'slug' => 'awp-dragon-lore',
                'description' => 'Skin legendaris AWP yang paling dicari kolektor.',
                'price' => 899000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $cs2->id,
                'category_id' => $voucher->id,
                'name' => 'CS2 Prime Status',
                'slug' => 'cs2-prime-status',
                'description' => 'Upgrade akun CS2 menjadi Prime untuk matchmaking yang lebih baik.',
                'price' => 199000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'code',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],

            // Valorant
            [
                'game_id' => $valorant->id,
                'category_id' => $weapon->id,
                'name' => 'Vandal Reaver',
                'slug' => 'vandal-reaver',
                'description' => 'Skin Vandal dengan tema kegelapan dan efek kill unik.',
                'price' => 249000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $valorant->id,
                'category_id' => $skin->id,
                'name' => 'Phantom Spectrum',
                'slug' => 'phantom-spectrum',
                'description' => 'Skin Phantom dengan musik elektronik dari Zedd.',
                'price' => 179000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $valorant->id,
                'category_id' => $currency->id,
                'name' => 'Valorant Points 1000',
                'slug' => 'valorant-points-1000',
                'description' => 'Top up 1000 Valorant Points.',
                'price' => 149000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'code',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],

            // Mobile Legends
            [
                'game_id' => $mlbb->id,
                'category_id' => $skin->id,
                'name' => 'Alucard Legendary Skin',
                'slug' => 'alucard-legendary-skin',
                'description' => 'Skin Legend Alucard Obsidian Blade.',
                'price' => 129000,
                'discount_price' => 99000,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $mlbb->id,
                'category_id' => $currency->id,
                'name' => 'Mobile Legends Diamonds 500',
                'slug' => 'mobile-legends-diamonds-500',
                'description' => 'Top up 500 Diamonds MLBB.',
                'price' => 89000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $mlbb->id,
                'category_id' => $voucher->id,
                'name' => 'Starlight Member',
                'slug' => 'starlight-member',
                'description' => 'Langganan Starlight Member bulanan.',
                'price' => 149000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],

            // Genshin Impact
            [
                'game_id' => $genshin->id,
                'category_id' => $weapon->id,
                'name' => 'Primordial Jade Spear',
                'slug' => 'primordial-jade-spear',
                'description' => 'Senjata bintang 5 Polearm.',
                'price' => 349000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $genshin->id,
                'category_id' => $currency->id,
                'name' => 'Genesis Crystal 1000',
                'slug' => 'genesis-crystal-1000',
                'description' => 'Top up 1000 Genesis Crystal.',
                'price' => 249000,
                'discount_price' => null,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
            [
                'game_id' => $genshin->id,
                'category_id' => $voucher->id,
                'name' => 'Genshin Blessing Moon',
                'slug' => 'genshin-blessing-moon',
                'description' => 'Blessing of the Welkin Moon 30 hari.',
                'price' => 79000,
                'discount_price' => 59000,
                'stock' => rand(10, 100),
                'delivery_type' => 'instant',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],

            // Multiple (assigned to Valorant for seeder simplicity)
            [
                'game_id' => $valorant->id,
                'category_id' => $bundle->id,
                'name' => 'Ultimate Gaming Bundle',
                'slug' => 'ultimate-gaming-bundle',
                'description' => 'Bundle spesial berisi berbagai item premium untuk banyak game.',
                'price' => 499000,
                'discount_price' => 399000,
                'stock' => rand(10, 100),
                'delivery_type' => 'code',
                'status' => 'active',
                'sold_count' => rand(0, 500),
                'rating' => rand(35, 50) / 10,
            ],
        ];

        foreach ($products as $index => $productData) {
            $product = Product::create($productData);

            // Create product image
            $product->images()->create([
                'image_path' => '/images/products/product'.($index % 5 + 1).'.jpg',
                'sort_order' => 1,
            ]);
        }
    }
}
