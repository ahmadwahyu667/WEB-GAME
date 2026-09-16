@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container section">
    <div style="margin-bottom: var(--space-2xl);">
        <h1 style="font-family: var(--font-heading); font-size: 2rem;">Keranjang Belanja</h1>
    </div>

    @if(!isset($cart) || empty($cart['items']) || (is_object($cart) && $cart->items->isEmpty()))
        <div class="card" style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 5rem; margin-bottom: var(--space-md); color: var(--text-muted);">🛒</div>
            <h2 style="font-family: var(--font-heading); margin-bottom: var(--space-sm);">Keranjang masih kosong</h2>
            <p style="color: var(--text-secondary); margin-bottom: var(--space-xl);">Yuk, cari item game favoritmu dan penuhi keranjang ini!</p>
            <a href="{{ route('products.index') }}" class="btn btn--primary">Mulai Belanja</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: var(--space-2xl); align-items: start;">
            
            {{-- Cart Items --}}
            <div>
                @php
                    $items = is_array($cart['items']) ? $cart['items'] : $cart->items;
                @endphp
                @foreach($items as $item)
                    @php
                        $product = $item->product ?? $item['product'];
                        $quantity = $item->quantity ?? $item['quantity'];
                        $id = $item->id ?? $item['id'];
                    @endphp
                    <div class="cart-item" id="cart-item-{{ $id }}">
                        <img src="{{ asset('storage/' . ($product->mainImage ?? 'placeholder.png')) }}" alt="{{ $product->name }}" class="cart-item__image">
                        
                        <div>
                            <div class="cart-item__name">{{ $product->name }}</div>
                            <div class="cart-item__game">{{ $product->game->name ?? '' }}</div>
                            <div style="color: var(--primary-cyan); font-weight: 600; font-size: 0.9rem; margin-top: 4px;">
                                Rp <span id="price-{{ $id }}">{{ number_format($product->effective_price ?? $product->price, 0, ',', '') }}</span>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                            <button type="button" onclick="updateCartItem({{ $id }}, -1)" style="padding: 6px 12px; background: none; border: none; color: var(--text-primary); cursor: pointer;">-</button>
                            <input type="number" id="qty-{{ $id }}" value="{{ $quantity }}" min="1" max="{{ $product->stock }}" style="width: 40px; text-align: center; background: none; border: none; color: var(--text-primary); font-weight: 600;" readonly>
                            <button type="button" onclick="updateCartItem({{ $id }}, 1)" style="padding: 6px 12px; background: none; border: none; color: var(--text-primary); cursor: pointer;">+</button>
                        </div>
                        
                        <div style="font-weight: 600; text-align: right; min-width: 100px;">
                            Rp <span class="item-subtotal" id="subtotal-{{ $id }}">{{ number_format(($product->effective_price ?? $product->price) * $quantity, 0, ',', '.') }}</span>
                        </div>

                        <button type="button" onclick="removeCartItem({{ $id }})" style="background: rgba(239, 68, 68, 0.1); border: none; color: #EF4444; width: 32px; height: 32px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.2)'" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'">
                            ✕
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Summary Sidebar --}}
            <div class="cart-summary">
                <h3 style="font-family: var(--font-heading); margin-bottom: var(--space-lg); padding-bottom: var(--space-sm); border-bottom: 1px solid var(--border-color);">Ringkasan Belanja</h3>
                
                @php
                    // Helper logic in view since we don't know if $cart is the array from getCartSummary or the model
                    $summaryService = app(\App\Services\CartService::class);
                    $summary = $summaryService->getCartSummary();
                @endphp

                <div class="cart-summary__row" style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--text-secondary);">
                    <span>Total Harga</span>
                    <span>Rp <span id="summary-subtotal">{{ number_format($summary['subtotal'], 0, ',', '.') }}</span></span>
                </div>
                
                <div class="cart-summary__row" style="display: flex; justify-content: space-between; margin-bottom: 16px; color: var(--primary-cyan);">
                    <span>Total Diskon</span>
                    <span>- Rp <span id="summary-discount">{{ number_format($summary['discount'], 0, ',', '.') }}</span></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-xl); padding-top: var(--space-md); border-top: 1px dashed var(--border-color); font-weight: 700; font-size: 1.25rem;">
                    <span>Total Bayar</span>
                    <span style="color: var(--primary-cyan);">Rp <span id="summary-total">{{ number_format($summary['total'], 0, ',', '.') }}</span></span>
                </div>

                @auth
                    <a href="{{ route('checkout.index') }}" class="btn btn--primary" style="width: 100%; text-align: center; margin-bottom: var(--space-md);">Lanjut Checkout</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn--primary" style="width: 100%; text-align: center; margin-bottom: var(--space-md);">Login untuk Checkout</a>
                @endauth
                
                <a href="{{ route('products.index') }}" class="btn btn--outline" style="width: 100%; text-align: center;">Lanjutkan Belanja</a>
            </div>

        </div>
    @endif
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 380px"] { grid-template-columns: 1fr !important; }
    .cart-summary { position: static !important; }
    .cart-item { grid-template-columns: 1fr 1fr; }
    .cart-item__image { grid-column: 1 / 3; width: 100%; height: 120px; }
}
</style>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // We rely on recalculating on the frontend, but saving to backend.
    async function updateCartItem(id, change) {
        const qtyInput = document.getElementById(`qty-${id}`);
        const currentQty = parseInt(qtyInput.value);
        const maxQty = parseInt(qtyInput.getAttribute('max'));
        const newQty = currentQty + change;
        
        if (newQty < 1) return;
        if (newQty > maxQty) {
            if (typeof showToast === 'function') showToast('Stok tidak mencukupi', 'error');
            return;
        }

        try {
            const response = await fetch(`/cart/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQty })
            });

            const data = await response.json();
            
            if (data.success) {
                qtyInput.value = newQty;
                recalculateTotals();
            } else {
                if (typeof showToast === 'function') showToast(data.message, 'error');
            }
        } catch (error) {
            console.error(error);
        }
    }

    async function removeCartItem(id) {
        if (!confirm('Hapus produk ini dari keranjang?')) return;

        try {
            const response = await fetch(`/cart/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                const itemEl = document.getElementById(`cart-item-${id}`);
                itemEl.remove();
                
                // If cart is empty, reload page to show empty state
                if (document.querySelectorAll('.cart-item').length === 0) {
                    window.location.reload();
                    return;
                }
                
                recalculateTotals();
                if (typeof showToast === 'function') showToast(data.message, 'success');
            }
        } catch (error) {
            console.error(error);
        }
    }

    function recalculateTotals() {
        let subtotal = 0;
        // Let's reload to get accurate discount logic from backend, 
        // as calculating exact discounts on frontend might be complex.
        // But for better UX, we just reload the page.
        window.location.reload();
    }
</script>
@endpush
@endsection
