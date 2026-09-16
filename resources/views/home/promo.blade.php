@extends('layouts.app')

@section('content')
<section class="section" style="padding-top: 120px; min-height: 80vh;">
    <div class="container">
        @include('components.breadcrumb', [
            'crumbs' => [
                ['label' => 'Promo', 'url' => url('/promo')]
            ]
        ])
        
        <div class="section-title text-left" style="text-align: left; margin-bottom: 32px;">
            <h2>Promo & <span class="accent">Penawaran Spesial</span></h2>
            <p>Dapatkan harga terbaik dengan berbagai promo diskon kami.</p>
        </div>
        
        <div class="product-grid">
            @forelse($promos as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">
                    <div class="empty-state__icon">🎁</div>
                    <h3 class="empty-state__title">Belum ada promo</h3>
                    <p class="empty-state__message">Saat ini sedang tidak ada produk promo yang aktif.</p>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 40px;">
            {{ $promos->links() }}
        </div>
    </div>
</section>

@push('scripts')
<script>
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
