@extends('layouts.app')

@section('content')

<!-- 1. Hero Section -->
<section class="hero">
    <div class="hero__bg"></div>
    <div class="hero__grid"></div>
    <div class="container hero__inner">
        <div class="hero__content">
            <div class="hero__badge">
                <span>🎮</span> Marketplace Game Terpercaya Indonesia
            </div>
            <h1 class="hero__title">
                Temukan Item Game <span class="gradient">Favoritmu</span>
            </h1>
            <p class="hero__subtitle">
                Belanja item game dengan mudah, cepat, dan praktis menggunakan QRIS.
            </p>
            <div class="hero__actions">
                <a href="{{ url('/products') }}" class="btn btn-primary btn-lg">Belanja Sekarang &rarr;</a>
                <a href="{{ url('/products') }}" class="btn btn-secondary btn-lg">Lihat Produk</a>
            </div>
            <div class="hero__stats">
                <div>
                    <div class="hero__stat-value">500+</div>
                    <div class="hero__stat-label">Produk</div>
                </div>
                <div>
                    <div class="hero__stat-value">10.000+</div>
                    <div class="hero__stat-label">Transaksi</div>
                </div>
                <div>
                    <div class="hero__stat-value">99%</div>
                    <div class="hero__stat-label">Kepuasan</div>
                </div>
            </div>
        </div>
        <div class="hero__artwork d-none d-md-block">
            <!-- Decorative gaming artwork using SVG/CSS shapes to match the dark theme -->
            <svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg" class="hero__artwork-img">
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:var(--primary-cyan);stop-opacity:1" />
                        <stop offset="100%" style="stop-color:var(--accent-purple);stop-opacity:1" />
                    </linearGradient>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="5" result="coloredBlur"/>
                        <feMerge>
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                <circle cx="200" cy="200" r="150" fill="none" stroke="var(--border-color)" stroke-width="2" stroke-dasharray="10, 10" />
                <polygon points="200,50 350,125 350,275 200,350 50,275 50,125" fill="none" stroke="url(#grad1)" stroke-width="4" filter="url(#glow)"/>
                <rect x="150" y="150" width="100" height="100" fill="url(#grad1)" rx="20" opacity="0.8" transform="rotate(45 200 200)"/>
                <circle cx="200" cy="200" r="30" fill="var(--bg-primary)" />
            </svg>
        </div>
    </div>
</section>

<!-- 2. Recent Purchases Carousel -->
<section class="recent-carousel">
    <div class="container" style="margin-bottom: 1rem;">
        <h3 style="font-size: 1rem; color: var(--text-secondary);">Pembelian Terbaru</h3>
    </div>
    <div class="recent-carousel__track">
        @forelse($recentOrders as $order)
            @foreach($order->items->take(1) as $item)
                <div class="recent-card">
                    <div class="recent-card__avatar">
                        {{ substr($order->user->name ?? 'User', 0, 1) }}
                    </div>
                    <div class="recent-card__info">
                        <div class="recent-card__product">{{ $item->product->name ?? 'Produk' }}</div>
                        <div class="recent-card__meta">{{ substr($order->user->name ?? 'User', 0, 3) }}*** • {{ $order->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="recent-card__price">
                        Rp{{ number_format($item->price, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        @empty
            @for($i=1; $i<=8; $i++)
                <div class="recent-card">
                    <div class="recent-card__avatar">U</div>
                    <div class="recent-card__info">
                        <div class="recent-card__product">Mobile Legends {{ $i*100 }} Diamonds</div>
                        <div class="recent-card__meta">Use*** • {{ $i }} menit yang lalu</div>
                    </div>
                    <div class="recent-card__price">Rp{{ number_format($i*15000, 0, ',', '.') }}</div>
                </div>
            @endfor
        @endforelse
    </div>
</section>

<!-- 5. Promo Section (Banner Carousel) -->
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Promo & <span class="accent">Penawaran Spesial</span></h2>
            <p>Dapatkan harga terbaik untuk game favoritmu</p>
        </div>
        
        <div class="banner-carousel" id="promo-carousel">
            @forelse($banners as $index => $banner)
                <div class="banner-slide {{ $index === 0 ? 'active' : '' }}">
                    @if($banner->image_path)
                        <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.3; z-index:1;">
                    @endif
                    <div class="banner-slide__content">
                        <h3 class="banner-slide__title">{{ $banner->title }}</h3>
                        <p class="banner-slide__subtitle">{{ $banner->description }}</p>
                        @if($banner->link)
                            <a href="{{ $banner->link }}" class="btn btn-primary">Lihat Promo</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="banner-slide active">
                    <div class="banner-slide__content">
                        <h3 class="banner-slide__title">Diskon Spesial Hari Ini!</h3>
                        <p class="banner-slide__subtitle">Top up game apa saja dapatkan diskon hingga 50%.</p>
                        <a href="{{ url('/promo') }}" class="btn btn-primary">Lihat Promo</a>
                    </div>
                </div>
            @endforelse
            
            @if(count($banners) > 1)
                <div class="banner-dots">
                    @foreach($banners as $index => $banner)
                        <button class="banner-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<!-- 4. Game Categories -->
<section class="section section--alt">
    <div class="container">
        <div class="section-title">
            <h2>Belanja Berdasarkan <span class="accent">Game</span></h2>
            <p>Pilih game favoritmu dan mulai belanja</p>
        </div>
        
        <div class="product-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
            @forelse($games as $game)
                <a href="{{ url('/games/' . $game->slug) }}" class="game-card">
                    @if($game->icon_path)
                        <img src="{{ asset('storage/' . $game->icon_path) }}" alt="{{ $game->name }}" class="game-card__logo">
                    @else
                        <div class="game-card__logo" style="background: var(--gradient-primary); display:flex; align-items:center; justify-content:center; color:white; font-size:24px; font-weight:bold;">
                            {{ substr($game->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="game-card__name">{{ $game->name }}</h3>
                    <p class="game-card__count">{{ $game->products_count }} Produk</p>
                </a>
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">Belum ada kategori game.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- 3. Popular Products -->
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Item Game <span class="accent">Terpopuler</span></h2>
            <p>Produk paling dicari oleh gamers</p>
        </div>
        
        <div class="tabs" id="popular-tabs">
            <button class="tab active" data-game="all">Semua</button>
            <button class="tab" data-game="Dota 2">Dota 2</button>
            <button class="tab" data-game="CS2">CS2</button>
            <button class="tab" data-game="Valorant">Valorant</button>
            <button class="tab" data-game="Mobile Legends">Mobile Legends</button>
            <button class="tab" data-game="Lainnya">Lainnya</button>
        </div>
        
        <div class="product-grid" id="popular-products">
            @forelse($popularProducts as $product)
                <div class="product-item" data-game="{{ optional($product->game)->name }}">
                    @include('components.product-card', ['product' => $product])
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">Belum ada produk populer.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- 6. Advantages -->
<section class="section section--alt">
    <div class="container">
        <div class="section-title">
            <h2>Kenapa Belanja di <span class="accent">GameMarket?</span></h2>
        </div>
        
        <div class="advantage-grid">
            <div class="advantage-card">
                <div class="advantage-card__icon">⚡</div>
                <h3 class="advantage-card__title">Transaksi Cepat</h3>
                <p class="advantage-card__desc">Proses instan dalam hitungan detik setelah pembayaran berhasil.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__icon">📱</div>
                <h3 class="advantage-card__title">Pembayaran QRIS</h3>
                <p class="advantage-card__desc">Dukung semua metode pembayaran digital melalui scan QRIS.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__icon">🔒</div>
                <h3 class="advantage-card__title">Proses Aman</h3>
                <p class="advantage-card__desc">Sistem keamanan tinggi untuk menjamin setiap transaksi aman 100%.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__icon">🎮</div>
                <h3 class="advantage-card__title">Produk Digital</h3>
                <p class="advantage-card__desc">Berbagai pilihan top-up game dan voucher digital lengkap.</p>
            </div>
        </div>
    </div>
</section>

<!-- 7. News Section -->
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Berita & Update <span class="accent">Gaming</span></h2>
        </div>
        
        <div class="news-grid">
            @if($news->count() > 0)
                <a href="{{ url('/news/' . $news[0]->slug) }}" class="news-card" style="grid-row: span 3; display: flex; flex-direction: column;">
                    <div class="news-card__image" style="flex: 1;">
                        <img src="{{ $news[0]->image_path ? asset('storage/' . $news[0]->image_path) : 'https://placehold.co/600x400/122235/00E5D4?text=News' }}" alt="{{ $news[0]->title }}">
                    </div>
                    <div class="news-card__body">
                        <div class="news-card__date">{{ $news[0]->created_at->format('d M Y') }}</div>
                        <h3 class="news-card__title" style="font-size: 1.25rem;">{{ $news[0]->title }}</h3>
                        <p class="news-card__excerpt">{{ Str::limit(strip_tags($news[0]->content), 120) }}</p>
                    </div>
                </a>
                
                @foreach($news->skip(1)->take(3) as $article)
                    <a href="{{ url('/news/' . $article->slug) }}" class="news-card" style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; align-items: center;">
                        <div class="news-card__image" style="aspect-ratio: 1; border-radius: 8px;">
                            <img src="{{ $article->image_path ? asset('storage/' . $article->image_path) : 'https://placehold.co/300x300/122235/00E5D4?text=News' }}" alt="{{ $article->title }}">
                        </div>
                        <div class="news-card__body" style="padding: 0;">
                            <div class="news-card__date">{{ $article->created_at->format('d M Y') }}</div>
                            <h3 class="news-card__title">{{ $article->title }}</h3>
                        </div>
                    </a>
                @endforeach
            @else
                <!-- Placeholders -->
                <div class="empty-state" style="grid-column: 1/-1;">Belum ada berita.</div>
            @endif
        </div>
        
        <div style="text-align: center; margin-top: 32px;">
            <a href="{{ url('/news') }}" class="btn btn-secondary">Baca Semua Berita</a>
        </div>
    </div>
</section>

<!-- 8. Partners Section -->
<section class="section section--alt">
    <div class="container">
        <div class="section-title">
            <h2>Partner <span class="accent">Kami</span></h2>
        </div>
        
        <div class="partner-grid">
            @for($i=1; $i<=6; $i++)
                <div class="partner-logo">
                    <span style="font-family: var(--font-heading); font-weight: 700; color: var(--text-muted);">PARTNER{{ $i }}</span>
                </div>
            @endfor
        </div>
    </div>
</section>

<!-- 9. Newsletter Section -->
<section class="section">
    <div class="container">
        <div class="newsletter">
            <h2 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 8px;">Tetap Terhubung dengan GameMarket</h2>
            <p style="color: var(--text-secondary);">Dapatkan info promo dan update game terbaru langsung di emailmu.</p>
            
            <form action="{{ url('/newsletter') }}" method="POST" class="newsletter__form" onsubmit="submitNewsletter(event, this)">
                @csrf
                <input type="email" name="email" class="form-input" placeholder="Masukkan alamat email..." required>
                <button type="submit" class="btn btn-primary">Berlangganan</button>
            </form>
        </div>
    </div>
</section>

@endsection

@stack('scripts')
<script>
    // Tab switching logic for Popular Products
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#popular-tabs .tab');
        const products = document.querySelectorAll('#popular-products .product-item');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active from all
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const targetGame = this.getAttribute('data-game');
                
                products.forEach(p => {
                    if (targetGame === 'all' || p.getAttribute('data-game') === targetGame) {
                        p.style.display = 'block';
                    } else if (targetGame === 'Lainnya') {
                        // Example logic for "Lainnya" - anything not in main tabs
                        const mainGames = ['Dota 2', 'CS2', 'Valorant', 'Mobile Legends'];
                        if (!mainGames.includes(p.getAttribute('data-game'))) {
                            p.style.display = 'block';
                        } else {
                            p.style.display = 'none';
                        }
                    } else {
                        p.style.display = 'none';
                    }
                });
            });
        });

        // Banner carousel rotation
        const slides = document.querySelectorAll('.banner-slide');
        const dots = document.querySelectorAll('.banner-dot');
        let currentSlide = 0;
        
        if(slides.length > 1) {
            setInterval(() => {
                slides[currentSlide].classList.remove('active');
                if(dots.length) dots[currentSlide].classList.remove('active');
                
                currentSlide = (currentSlide + 1) % slides.length;
                
                slides[currentSlide].classList.add('active');
                if(dots.length) dots[currentSlide].classList.add('active');
            }, 5000);
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    slides[currentSlide].classList.remove('active');
                    dots[currentSlide].classList.remove('active');
                    currentSlide = index;
                    slides[currentSlide].classList.add('active');
                    dots[currentSlide].classList.add('active');
                });
            });
        }
    });

    // Cart fetch submission
    async function addToCart(e, form) {
        e.preventDefault();
        const btn = form.querySelector('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '...';
        btn.disabled = true;

        try {
            const formData = new FormData(form);
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            if(response.ok) {
                // Success - could show a toast here using components.css toast
                alert('Produk ditambahkan ke keranjang!');
                // Update cart count if exists
                const cartCount = document.querySelector('.navbar__cart-count');
                if(cartCount) {
                    cartCount.innerText = parseInt(cartCount.innerText) + 1;
                } else {
                    // Create badge if not exists
                    const btn = document.querySelector('.navbar__cart-btn');
                    if (btn) btn.innerHTML += '<span class="navbar__cart-count">1</span>';
                }
            } else {
                if(response.status === 401) {
                    window.location.href = '/login';
                } else {
                    alert('Gagal menambahkan ke keranjang');
                }
            }
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan sistem.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    // Newsletter fetch submission
    async function submitNewsletter(e, form) {
        e.preventDefault();
        const btn = form.querySelector('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Loading...';
        btn.disabled = true;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            if(response.ok) {
                alert('Terima kasih telah berlangganan!');
                form.reset();
            } else {
                alert('Gagal berlangganan. Silakan coba lagi.');
            }
        } catch (error) {
            console.error(error);
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>
