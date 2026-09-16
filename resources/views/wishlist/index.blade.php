@extends('layouts.app')
@section('title', 'Wishlist Saya')

@section('content')
<div class="section container">
    <div style="margin-bottom: var(--space-2xl);">
        <h1 style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: var(--text-primary);">Wishlist Saya</h1>
        <p style="color: var(--text-secondary);">Daftar game dan item yang Anda simpan.</p>
    </div>

    @if($wishlists->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: var(--space-lg);">
            @foreach($wishlists as $wishlist)
                @if($wishlist->product)
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column; position: relative;">
                        
                        {{-- Remove button --}}
                        <button type="button" onclick="toggleWishlist({{ $wishlist->product->id }}, this)" style="position: absolute; top: 12px; right: 12px; width: 32px; height: 32px; border-radius: 50%; background: rgba(0,0,0,0.5); border: none; color: #EF4444; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10;">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>

                        <img src="{{ $wishlist->product->image ?? 'https://via.placeholder.com/220x150' }}" alt="{{ $wishlist->product->name }}" style="width: 100%; aspect-ratio: 4/3; object-fit: cover;">
                        
                        <div style="padding: var(--space-md); flex: 1; display: flex; flex-direction: column;">
                            <div style="color: var(--text-muted); font-size: 0.75rem; margin-bottom: 4px;">{{ $wishlist->product->category->name ?? 'Kategori' }}</div>
                            <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; flex: 1;">{{ $wishlist->product->name }}</h3>
                            <div style="font-weight: 700; color: var(--primary-cyan); margin-bottom: var(--space-md);">Rp {{ number_format($wishlist->product->price, 0, ',', '.') }}</div>
                            
                            <form action="{{ route('wishlist.to_cart', $wishlist->product->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="width: 100%; padding: 8px; background: var(--gradient-primary); color: var(--bg-primary); border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: var(--space-3xl) 0; background: var(--bg-card); border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
            <div style="color: #EF4444; font-size: 3rem; margin-bottom: var(--space-md);">
                <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24" style="margin: 0 auto;"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </div>
            <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Wishlist masih kosong</h3>
            <p style="color: var(--text-secondary); margin-bottom: var(--space-lg);">Anda belum menyimpan item apapun ke dalam wishlist.</p>
            <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 24px; background: var(--gradient-primary); color: var(--bg-primary); border-radius: var(--radius-full); font-weight: 600; text-decoration: none;">Jelajahi Produk</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleWishlist(productId, btnElement) {
    fetch(`/wishlist/${productId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success && data.status === 'removed') {
            // Remove card from UI immediately
            const card = btnElement.closest('div');
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();
                // Optional: reload if empty
                if(document.querySelectorAll('button[onclick^="toggleWishlist"]').length === 0) {
                    window.location.reload();
                }
            }, 300);
        }
    })
    .catch(err => console.error('Error toggling wishlist', err));
}
</script>
@endpush
