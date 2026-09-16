<nav class="navbar navbar--transparent" id="navbar">
    <div class="container navbar__inner">
        <a href="{{ url('/') }}" class="navbar__logo">
            <div class="navbar__logo-icon">⚡</div>
            GameMarket
        </a>

        <div class="navbar__search">
            <span class="navbar__search-icon">🔍</span>
            <form action="{{ url('/products') }}" method="GET">
                <input type="text" name="search" class="navbar__search-input" placeholder="Cari item atau game..." value="{{ request('search') }}">
            </form>
        </div>

        <ul class="navbar__menu" id="nav-menu">
            <li><a href="{{ url('/games') }}" class="navbar__link {{ request()->is('games*') ? 'active' : '' }}">Game</a></li>
            <li><a href="{{ url('/products') }}" class="navbar__link {{ request()->is('products*') ? 'active' : '' }}">Produk</a></li>
            <li><a href="{{ url('/promo') }}" class="navbar__link {{ request()->is('promo*') ? 'active' : '' }}">Promo</a></li>
            <li><a href="{{ url('/news') }}" class="navbar__link {{ request()->is('news*') ? 'active' : '' }}">Berita</a></li>
            <li><a href="{{ url('/faq') }}" class="navbar__link {{ request()->is('faq*') ? 'active' : '' }}">FAQ</a></li>
        </ul>

        <div class="navbar__actions">
            @auth
                <a href="{{ url('/cart') }}" class="navbar__cart-btn">
                    🛒
                    @php $cartCount = auth()->user()->cart?->items?->count() ?? 0; @endphp
                    @if($cartCount > 0)
                        <span class="navbar__cart-count">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="user-dropdown">
                    <!-- Dropdown can be styled further with components css if present -->
                    <a href="{{ url('/profile') }}" class="btn btn-ghost btn-sm">Profil</a>
                    <a href="{{ url('/orders') }}" class="btn btn-ghost btn-sm">Pesanan</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Login</a>
                <a href="{{ url('/products') }}" class="btn btn-primary btn-sm">Mulai Belanja</a>
            @endauth

            <button class="navbar__toggle" id="nav-toggle">☰</button>
        </div>
    </div>
</nav>

@stack('navbar-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const navToggle = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('nav-menu');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.remove('navbar--transparent');
                navbar.classList.add('navbar--solid');
            } else {
                navbar.classList.add('navbar--transparent');
                navbar.classList.remove('navbar--solid');
            }
        });

        if (navToggle) {
            navToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active'); // Basic mobile toggle (needs responsive css logic)
            });
        }
    });
</script>
