<div class="card product-card">
    <div class="card__image">
        @if($product->sold_count > 100)
            <span class="card__badge">HOT</span>
        @elseif($product->discount_price)
            <span class="card__badge">PROMO</span>
        @else
            <span class="card__badge" style="background: var(--primary-cyan);">NEW</span>
        @endif
        @php
            $imgSrc = 'https://placehold.co/400x300/122235/00E5D4?text=No+Image';
            if ($product->main_image && $product->main_image !== 'placeholder.png') {
                $imgSrc = asset('storage/' . $product->main_image);
            } elseif ($product->images && $product->images->isNotEmpty()) {
                $imgSrc = asset('storage/' . $product->images->first()->image_path);
            }
        @endphp
        <a href="{{ url('/products/' . $product->slug) }}">
            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" onerror="this.src='https://placehold.co/400x300/122235/00E5D4?text=No+Image'">
        </a>
    </div>
    
    <div class="card__body">
        <h3 class="card__title"><a href="{{ url('/products/' . $product->slug) }}">{{ $product->name }}</a></h3>
        <p class="card__subtitle">{{ optional($product->game)->name }}</p>
        
        <div class="price">
            @if($product->discount_price)
                <span class="price__original">{{ $product->price_formatted ?? 'Rp' . number_format($product->price, 0, ',', '.') }}</span>
                <div style="display:flex; align-items:center; gap: 8px;">
                    <span class="price__current">{{ $product->discount_price_formatted ?? 'Rp' . number_format($product->discount_price, 0, ',', '.') }}</span>
                    @php 
                        $discountPercent = round((($product->price - $product->discount_price) / $product->price) * 100);
                    @endphp
                    <span class="price__discount">-{{ $discountPercent }}%</span>
                </div>
            @else
                <span class="price__current">{{ $product->price_formatted ?? 'Rp' . number_format($product->price, 0, ',', '.') }}</span>
            @endif
        </div>
        
        <div class="product-stock mt-2" style="font-size:0.8rem; color:var(--text-secondary); margin-top:8px;">
            Stok: {{ $product->stock > 0 ? $product->stock : 'Habis' }}
        </div>
    </div>
    
    <div class="card__footer">
        <a href="{{ url('/products/' . $product->slug) }}" class="btn btn-secondary btn-sm" style="flex:1;">Beli</a>
        <form class="cart-form" style="display:inline;" onsubmit="addToCart(event, this)">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-primary btn-sm btn-icon" {{ $product->stock <= 0 ? 'disabled' : '' }} title="Tambah ke Keranjang">🛒</button>
        </form>
    </div>
</div>
