@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container section">
    {{-- Breadcrumb --}}
    <nav class="breadcrumb" style="margin-bottom: var(--space-xl); color: var(--text-secondary); font-size: 0.85rem;">
        <a href="{{ route('home') }}">Home</a> &gt; 
        <a href="{{ route('products.index') }}">Produk</a> &gt; 
        <a href="{{ route('products.index', ['game' => $product->game->slug]) }}">{{ $product->game->name }}</a> &gt; 
        <span>{{ $product->name }}</span>
    </nav>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-3xl); align-items: start; margin-bottom: var(--space-3xl);">
        
        {{-- Left: Images --}}
        <div>
            <div class="card" style="padding: var(--space-sm); border-radius: var(--radius-xl); overflow: hidden; margin-bottom: var(--space-md);">
                @if($product->mainImage)
                    <img src="{{ asset('storage/' . $product->mainImage) }}" alt="{{ $product->name }}" style="width: 100%; aspect-ratio: 4/3; object-fit: cover; border-radius: var(--radius-lg);">
                @else
                    <div style="width: 100%; aspect-ratio: 4/3; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; border-radius: var(--radius-lg);">
                        No Image
                    </div>
                @endif
            </div>
            
            @if($product->images->count() > 1)
                <div style="display: flex; gap: var(--space-sm); overflow-x: auto; padding-bottom: var(--space-sm);">
                    @foreach($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail" style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-md); border: 2px solid {{ $loop->first ? 'var(--primary-cyan)' : 'transparent' }}; cursor: pointer;">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: Details --}}
        <div>
            <div style="display: inline-block; padding: 4px 12px; background: rgba(122, 44, 255, 0.1); border: 1px solid rgba(122, 44, 255, 0.2); border-radius: var(--radius-full); font-size: 0.8rem; color: var(--accent-purple); font-weight: 600; margin-bottom: var(--space-sm);">
                {{ $product->game->name }}
            </div>
            
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: var(--space-sm); line-height: 1.2;">{{ $product->name }}</h1>
            
            @if($product->rating > 0)
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: var(--space-md);">
                    <span style="color: #F59E0B;">★</span>
                    <span style="font-weight: 600;">{{ $product->rating }}</span>
                    <span style="color: var(--text-muted); font-size: 0.9rem;">({{ $product->reviews->count() }} ulasan)</span>
                    <span style="color: var(--text-muted); font-size: 0.9rem;">• {{ $product->sold_count }} Terjual</span>
                </div>
            @endif

            <div style="margin-bottom: var(--space-xl); padding-bottom: var(--space-lg); border-bottom: 1px solid var(--border-color);">
                @if($product->discount_price > 0 && $product->discount_price < $product->price)
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                        <span style="text-decoration: line-through; color: var(--text-muted); font-size: 1.1rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span style="background: rgba(0, 229, 212, 0.1); color: var(--primary-cyan); padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                            Disk {{ $product->discount_percentage }}%
                        </span>
                    </div>
                    <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: 700; color: var(--primary-cyan);">
                        Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                    </div>
                @else
                    <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: 700; color: var(--primary-cyan);">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                @endif
            </div>

            @if($product->status == 'active')
                <div style="margin-bottom: var(--space-xl);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 600;">Kuantitas</span>
                        @if($product->stock > 0)
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">Stok: {{ $product->stock }} tersedia</span>
                        @else
                            <span style="font-size: 0.9rem; color: #EF4444; font-weight: 600;">Habis</span>
                        @endif
                    </div>
                    
                    <div style="display: flex; gap: var(--space-md); align-items: center;">
                        <div style="display: flex; align-items: center; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                            <button type="button" onclick="updateQty(-1)" style="padding: 10px 16px; background: none; border: none; color: var(--text-primary); cursor: pointer; font-size: 1.2rem;" {{ $product->stock <= 0 ? 'disabled' : '' }}>-</button>
                            <input type="number" id="qty" value="1" min="1" max="{{ $product->stock }}" style="width: 50px; text-align: center; background: none; border: none; color: var(--text-primary); font-weight: 600;" readonly>
                            <button type="button" onclick="updateQty(1)" style="padding: 10px 16px; background: none; border: none; color: var(--text-primary); cursor: pointer; font-size: 1.2rem;" {{ $product->stock <= 0 ? 'disabled' : '' }}>+</button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: var(--space-md);">
                    <button type="button" onclick="handleAddToCartClick({{ $product->id }})" class="btn btn--outline" style="flex: 1;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        🛒 + Keranjang
                    </button>
                    <button type="button" onclick="openBuyNowModal()" class="btn btn--primary" style="flex: 1;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        ⚡ Beli Sekarang
                    </button>
                </div>
            @else
                <div class="card" style="padding: var(--space-lg); text-align: center; background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2);">
                    <span style="color: #EF4444; font-weight: 600; font-size: 1.1rem;">Produk tidak tersedia</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Tabs --}}
    <div class="card" style="margin-bottom: var(--space-3xl); overflow: hidden;">
        <div style="display: flex; border-bottom: 1px solid var(--border-color); background: var(--bg-secondary);">
            <button class="tab-btn active" style="padding: 16px 24px; background: none; border: none; border-bottom: 2px solid var(--primary-cyan); color: var(--primary-cyan); font-weight: 600; cursor: pointer;">Deskripsi</button>
            <button class="tab-btn" style="padding: 16px 24px; background: none; border: none; border-bottom: 2px solid transparent; color: var(--text-secondary); font-weight: 600; cursor: pointer;">Informasi Produk</button>
            <button class="tab-btn" style="padding: 16px 24px; background: none; border: none; border-bottom: 2px solid transparent; color: var(--text-secondary); font-weight: 600; cursor: pointer;">Cara Penggunaan</button>
        </div>
        <div style="padding: var(--space-xl);">
            <div id="tab-deskripsi" style="line-height: 1.8; color: var(--text-secondary);">
                {!! nl2br(e($product->description)) !!}
            </div>
            <div id="tab-informasi" style="display: none; line-height: 1.8; color: var(--text-secondary);">
                <ul style="list-style: none; padding: 0;">
                    <li style="display: grid; grid-template-columns: 150px 1fr; margin-bottom: 8px;"><strong>Tipe Pengiriman:</strong> <span>{{ $product->delivery_type }}</span></li>
                    <li style="display: grid; grid-template-columns: 150px 1fr; margin-bottom: 8px;"><strong>Game:</strong> <span>{{ $product->game->name }}</span></li>
                    <li style="display: grid; grid-template-columns: 150px 1fr; margin-bottom: 8px;"><strong>Kategori:</strong> <span>{{ $product->category->name }}</span></li>
                </ul>
            </div>
            <div id="tab-cara" style="display: none; line-height: 1.8; color: var(--text-secondary);">
                {!! nl2br(e($product->delivery_instruction)) !!}
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: var(--space-lg);">Produk Terkait</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: var(--space-lg);">
                @foreach($relatedProducts as $related)
                    @include('components.product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Buy Now Modal --}}
<div class="modal-backdrop" id="buyNowModal" onclick="handleBackdropClick(event)">
    <div class="modal" style="max-width: 520px; width: 95%; max-height: 90vh; overflow-y: auto; padding: var(--space-xl);">
        <div class="modal__header" style="border-bottom: 1px solid var(--border-color); padding-bottom: var(--space-md); margin-bottom: var(--space-lg);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 1.3rem;">⚡</span>
                <h3 class="modal__title" style="margin: 0; font-size: 1.25rem;">Beli Sekarang</h3>
            </div>
            <button type="button" class="modal__close" onclick="closeBuyNowModal()" aria-label="Tutup">&times;</button>
        </div>

        <div class="modal__body" style="color: var(--text-primary);">
            {{-- Product Preview --}}
            <div style="display: flex; gap: var(--space-md); background: var(--bg-secondary); padding: var(--space-md); border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: var(--space-lg); align-items: center;">
                @php
                    $imgSrc = 'https://placehold.co/400x300/122235/00E5D4?text=No+Image';
                    if ($product->mainImage) {
                        $imgSrc = asset('storage/' . $product->mainImage);
                    } elseif ($product->images && $product->images->isNotEmpty()) {
                        $imgSrc = asset('storage/' . $product->images->first()->image_path);
                    }
                    $effectivePrice = ($product->discount_price > 0 && $product->discount_price < $product->price) ? $product->discount_price : $product->price;
                @endphp
                <img src="{{ $imgSrc }}" alt="{{ $product->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: var(--radius-md); flex-shrink: 0;" onerror="this.src='https://placehold.co/400x300/122235/00E5D4?text=No+Image'">
                <div style="flex: 1; min-width: 0;">
                    <span style="display: inline-block; font-size: 0.75rem; color: var(--accent-purple); font-weight: 600; text-transform: uppercase;">{{ $product->game->name }}</span>
                    <h4 style="font-size: 1rem; margin: 2px 0 6px 0; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</h4>
                    <div style="font-size: 0.95rem; font-weight: 700; color: var(--primary-cyan);">
                        Rp <span>{{ number_format($effectivePrice, 0, ',', '.') }}</span>
                        @if($product->discount_price > 0 && $product->discount_price < $product->price)
                            <span style="text-decoration: line-through; color: var(--text-muted); font-size: 0.8rem; margin-left: 6px;">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quantity Selector inside Modal --}}
            <div style="margin-bottom: var(--space-lg); background: var(--bg-secondary); padding: var(--space-md); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label style="font-weight: 600; font-size: 0.9rem;">Jumlah Pembelian</label>
                    <span style="font-size: 0.8rem; color: var(--text-secondary);">Tersedia: {{ $product->stock }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    <div style="display: inline-flex; align-items: center; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                        <button type="button" onclick="updateModalQty(-1)" style="padding: 8px 14px; background: none; border: none; color: var(--text-primary); cursor: pointer; font-size: 1.1rem;">-</button>
                        <input type="number" id="modal-qty" value="1" min="1" max="{{ $product->stock }}" style="width: 50px; text-align: center; background: none; border: none; color: var(--text-primary); font-weight: 700;" readonly>
                        <button type="button" onclick="updateModalQty(1)" style="padding: 8px 14px; background: none; border: none; color: var(--text-primary); cursor: pointer; font-size: 1.1rem;">+</button>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                        Maks. {{ $product->stock }} item
                    </div>
                </div>
            </div>

            @auth
                {{-- Checkout Form for Logged in Users --}}
                <form action="{{ route('checkout.direct') }}" method="POST" id="buy-now-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="form-direct-qty" value="1">
                    <input type="hidden" name="payment_method" value="qris">

                    <div style="margin-bottom: var(--space-md);">
                        <label for="buy_now_customer_name" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-secondary);">Nama Pembeli</label>
                        <input type="text" id="buy_now_customer_name" name="customer_name" class="form-input" value="{{ old('customer_name', auth()->user()->name) }}" required style="width: 100%; padding: 10px 14px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); color: var(--text-primary);">
                    </div>

                    <div style="margin-bottom: var(--space-md);">
                        <label for="buy_now_customer_email" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-secondary);">Email</label>
                        <input type="email" id="buy_now_customer_email" name="customer_email" class="form-input" value="{{ old('customer_email', auth()->user()->email) }}" required style="width: 100%; padding: 10px 14px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); color: var(--text-primary);">
                    </div>

                    <div style="margin-bottom: var(--space-lg);">
                        <label for="buy_now_customer_whatsapp" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-secondary);">Nomor WhatsApp (Aktif)</label>
                        <input type="text" id="buy_now_customer_whatsapp" name="customer_whatsapp" class="form-input" value="{{ old('customer_whatsapp', auth()->user()->phone ?? '') }}" placeholder="Contoh: 081234567890" required style="width: 100%; padding: 10px 14px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); color: var(--text-primary);">
                    </div>

                    {{-- Payment Method Selection --}}
                    <div style="margin-bottom: var(--space-lg);">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: var(--text-secondary);">Metode Pembayaran</label>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(0, 229, 212, 0.08); border: 1.5px solid var(--primary-cyan); border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 1.3rem;">📱</span>
                                <div>
                                    <div style="font-weight: 600; font-size: 0.95rem; color: var(--primary-cyan);">QRIS (Otomatis & Real-time)</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary);">GoPay, OVO, DANA, BCA, Mandiri, BRI, dll</div>
                                </div>
                            </div>
                            <span style="color: var(--primary-cyan); font-weight: bold; font-size: 1.1rem;">✓</span>
                        </div>
                    </div>

                    {{-- Summary Box --}}
                    <div style="background: var(--bg-secondary); border-radius: var(--radius-md); padding: var(--space-md); margin-bottom: var(--space-lg); border: 1px dashed var(--border-color);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.85rem; color: var(--text-secondary);">
                            <span>Total Item</span>
                            <span id="modal-summary-qty">1 item</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700; color: var(--text-primary); padding-top: 6px; border-top: 1px solid var(--border-color);">
                            <span>Total Bayar</span>
                            <span style="color: var(--primary-cyan);">Rp <span id="modal-total-bayar">{{ number_format($effectivePrice, 0, ',', '.') }}</span></span>
                        </div>
                    </div>

                    <button type="submit" id="modal-submit-btn" class="btn btn--primary" style="width: 100%; padding: 14px; font-size: 1rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span>Lanjut ke Pembayaran QRIS</span>
                        <span>→</span>
                    </button>
                </form>
            @else
                {{-- Guest Notice --}}
                <div style="background: rgba(122, 44, 255, 0.1); border: 1px solid rgba(122, 44, 255, 0.25); border-radius: var(--radius-md); padding: var(--space-lg); text-align: center; margin-bottom: var(--space-lg);">
                    <div style="font-size: 2rem; margin-bottom: 8px;">🔐</div>
                    <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 6px; color: var(--text-primary);">Login untuk Melanjutkan</h4>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: var(--space-md);">
                        Silakan masuk ke akun Anda agar transaksi tercatat aman dan kode/item langsung dikirimkan ke akun Anda.
                    </p>
                    
                    {{-- Summary Box for Guest --}}
                    <div style="background: var(--bg-secondary); border-radius: var(--radius-md); padding: var(--space-sm) var(--space-md); margin-bottom: var(--space-md); display: flex; justify-content: space-between; font-size: 0.9rem; font-weight: 600;">
                        <span style="color: var(--text-secondary);">Estimasi Total:</span>
                        <span style="color: var(--primary-cyan);">Rp <span id="modal-guest-total">{{ number_format($effectivePrice, 0, ',', '.') }}</span></span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="btn btn--primary" style="width: 100%; text-align: center; padding: 12px; font-weight: 600;">
                            Masuk Sekarang
                        </a>
                        <a href="{{ route('register') }}" class="btn btn--outline" style="width: 100%; text-align: center; padding: 12px;">
                            Belum Punya Akun? Daftar
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
}
</style>

@push('scripts')
<script>
    const maxQty = {{ $product->stock }};
    const unitPrice = {{ $effectivePrice }};
    const qtyInput = document.getElementById('qty');
    const modalQtyInput = document.getElementById('modal-qty');
    const formDirectQty = document.getElementById('form-direct-qty');
    const modalSummaryQty = document.getElementById('modal-summary-qty');
    const modalTotalBayar = document.getElementById('modal-total-bayar');
    const modalGuestTotal = document.getElementById('modal-guest-total');

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function updateQty(change) {
        let current = parseInt(qtyInput.value) || 1;
        let next = current + change;
        if(next >= 1 && next <= maxQty) {
            qtyInput.value = next;
            syncToModal(next);
        }
    }

    function updateModalQty(change) {
        let current = parseInt(modalQtyInput ? modalQtyInput.value : 1) || 1;
        let next = current + change;
        if(next >= 1 && next <= maxQty) {
            syncToModal(next);
            if (qtyInput) qtyInput.value = next;
        }
    }

    function syncToModal(qty) {
        if (modalQtyInput) modalQtyInput.value = qty;
        if (formDirectQty) formDirectQty.value = qty;
        if (modalSummaryQty) modalSummaryQty.textContent = qty + ' item';
        const total = qty * unitPrice;
        if (modalTotalBayar) modalTotalBayar.textContent = formatNumber(total);
        if (modalGuestTotal) modalGuestTotal.textContent = formatNumber(total);
    }

    function openBuyNowModal() {
        const currentQty = parseInt(qtyInput?.value || 1);
        syncToModal(currentQty);

        const modal = document.getElementById('buyNowModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeBuyNowModal() {
        const modal = document.getElementById('buyNowModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function handleBackdropClick(e) {
        if (e.target && e.target.id === 'buyNowModal') {
            closeBuyNowModal();
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeBuyNowModal();
        }
    });

    async function handleAddToCartClick(productId) {
        const qty = parseInt(qtyInput?.value || 1);
        if (typeof window.addToCart === 'function') {
            await window.addToCart(productId, qty);
        }
    }

    // Tabs functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = [
        document.getElementById('tab-deskripsi'),
        document.getElementById('tab-informasi'),
        document.getElementById('tab-cara')
    ];

    tabBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            // reset all
            tabBtns.forEach(b => {
                b.classList.remove('active');
                b.style.borderBottomColor = 'transparent';
                b.style.color = 'var(--text-secondary)';
            });
            tabContents.forEach(c => c.style.display = 'none');
            
            // set active
            btn.classList.add('active');
            btn.style.borderBottomColor = 'var(--primary-cyan)';
            btn.style.color = 'var(--primary-cyan)';
            tabContents[index].style.display = 'block';
        });
    });
</script>
@endpush
@endsection
