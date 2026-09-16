@extends('layouts.app')

@section('content')
<section class="section" style="padding-top: 120px; min-height: 80vh;">
    <div class="container">
        @include('components.breadcrumb', [
            'crumbs' => [
                ['label' => 'Games', 'url' => url('/games')],
                ['label' => $game->name, 'url' => url('/games/' . $game->slug)]
            ]
        ])
        
        <div class="game-header" style="display:flex; align-items:center; gap: 24px; margin-bottom: 40px; background: var(--bg-card); padding: 32px; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
            @if($game->icon_path)
                <img src="{{ asset('storage/' . $game->icon_path) }}" alt="{{ $game->name }}" style="width: 100px; height: 100px; border-radius: var(--radius-lg); object-fit:cover;">
            @else
                <div style="width: 100px; height: 100px; border-radius: var(--radius-lg); background: var(--gradient-primary); display:flex; align-items:center; justify-content:center; color:white; font-size:36px; font-weight:bold;">
                    {{ substr($game->name, 0, 1) }}
                </div>
            @endif
            
            <div>
                <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--text-primary); margin-bottom: 8px;">{{ $game->name }}</h1>
                <p style="color: var(--text-secondary);">Temukan berbagai item dan produk untuk game {{ $game->name }}.</p>
            </div>
        </div>
        
        <div class="product-grid">
            @forelse($products as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">
                    <div class="empty-state__icon">🛍️</div>
                    <h3 class="empty-state__title">Belum ada produk</h3>
                    <p class="empty-state__message">Belum ada produk untuk game ini.</p>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 40px;">
            {{ $products->links() }}
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Copy the add to cart logic from index if needed, or include from a common JS file.
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
                alert('Produk ditambahkan ke keranjang!');
                const cartCount = document.querySelector('.navbar__cart-count');
                if(cartCount) {
                    cartCount.innerText = parseInt(cartCount.innerText) + 1;
                } else {
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
</script>
@endpush
@endsection
