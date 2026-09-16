<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            [
                'name' => 'Dota 2',
                'slug' => 'dota-2',
                'description' => 'Game MOBA populer dari Valve.',
                'logo' => '/images/games/dota2.svg',
                'banner' => '/images/games/banners/dota2.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Counter-Strike 2',
                'slug' => 'counter-strike-2',
                'description' => 'Game FPS taktis legendaris.',
                'logo' => '/images/games/cs2.svg',
                'banner' => '/images/games/banners/cs2.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Valorant',
                'slug' => 'valorant',
                'description' => 'Game hero shooter kompetitif dari Riot Games.',
                'logo' => '/images/games/valorant.svg',
                'banner' => '/images/games/banners/valorant.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Mobile Legends',
                'slug' => 'mobile-legends',
                'description' => 'Game MOBA mobile 5v5 dari Moonton.',
                'logo' => '/images/games/mobile-legends.svg',
                'banner' => '/images/games/banners/mobile-legends.svg',
                'status' => 'active',
            ],
            [
                'name' => 'Genshin Impact',
                'slug' => 'genshin-impact',
                'description' => 'Game action RPG open-world populer.',
                'logo' => '/images/games/genshin-impact.svg',
                'banner' => '/images/games/banners/genshin-impact.svg',
                'status' => 'active',
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
