<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsArticles = [
            [
                'title' => 'Update Patch Dota 2 Terbaru: Perubahan Hero dan Item',
                'slug' => 'update-patch-dota-2-terbaru-perubahan-hero-dan-item',
                'thumbnail' => '/images/news/news1.jpg',
                'content' => '<p>Valve baru saja merilis update patch terbaru untuk Dota 2 yang membawa banyak perubahan signifikan pada meta permainan. Beberapa hero favorit mendapatkan penyesuaian status dan skill, sementara beberapa item juga mengalami perubahan resep dan efek.</p><p>Pemain diharapkan segera menyesuaikan diri dengan perubahan ini. Patch ini juga membawa perbaikan bug minor dan peningkatan performa sistem secara keseluruhan.</p>',
                'excerpt' => 'Valve baru saja merilis update patch terbaru untuk Dota 2 yang membawa banyak perubahan signifikan.',
                'author' => 'Admin GameMarket',
                'published_at' => now()->subDays(2),
                'status' => 'published',
            ],
            [
                'title' => 'Turnamen CS2 Major 2026: Jadwal dan Tim Favorit',
                'slug' => 'turnamen-cs2-major-2026-jadwal-dan-tim-favorit',
                'thumbnail' => '/images/news/news2.jpg',
                'content' => '<p>Turnamen paling bergengsi untuk Counter-Strike 2 akan segera digelar bulan depan. Tim-tim esports teratas dari seluruh dunia sudah mempersiapkan diri untuk memperebutkan gelar juara dan total hadiah jutaan dolar.</p><p>Beberapa tim unggulan seperti Natus Vincere dan FaZe Clan diprediksi akan memberikan perlawanan sengit. Para penggemar bisa membeli tiket untuk menonton langsung atau menonton streaming di platform resmi.</p>',
                'excerpt' => 'Turnamen paling bergengsi untuk Counter-Strike 2 akan segera digelar bulan depan dengan tim-tim terbaik.',
                'author' => 'Admin GameMarket',
                'published_at' => now()->subDays(5),
                'status' => 'published',
            ],
            [
                'title' => 'Valorant Episode Baru: Agent dan Map Baru',
                'slug' => 'valorant-episode-baru-agent-dan-map-baru',
                'thumbnail' => '/images/news/news3.jpg',
                'content' => '<p>Riot Games resmi mengumumkan kedatangan Episode baru di Valorant yang akan memperkenalkan seorang Agent Controller baru dan map dengan mekanik unik. Agent baru ini diharapkan dapat mengubah cara tim bermain secara taktis.</p><p>Selain itu, sistem rank juga mendapatkan beberapa penyesuaian agar lebih adil bagi pemain solo dan party. Bersiaplah untuk grinding rank di musim baru ini!</p>',
                'excerpt' => 'Riot Games resmi mengumumkan kedatangan Episode baru di Valorant yang memperkenalkan Agent dan Map baru.',
                'author' => 'Admin GameMarket',
                'published_at' => now()->subWeek(),
                'status' => 'published',
            ],
            [
                'title' => 'Mobile Legends: Event Spesial dan Skin Gratis',
                'slug' => 'mobile-legends-event-spesial-dan-skin-gratis',
                'thumbnail' => '/images/news/news4.jpg',
                'content' => '<p>Moonton kembali memanjakan para pemain Mobile Legends dengan mengadakan event login spesial yang berhadiah skin Epic gratis. Pemain hanya perlu menyelesaikan beberapa misi harian dan login berturut-turut selama satu minggu.</p><p>Event ini juga menghadirkan diskon besar-besaran untuk pembelian skin dan hero di dalam shop. Jangan lewatkan kesempatan emas ini untuk melengkapi koleksi skin kalian.</p>',
                'excerpt' => 'Moonton kembali memanjakan para pemain Mobile Legends dengan mengadakan event berhadiah skin Epic.',
                'author' => 'Admin GameMarket',
                'published_at' => now()->subDays(10),
                'status' => 'published',
            ],
        ];

        foreach ($newsArticles as $news) {
            News::create($news);
        }
    }
}
