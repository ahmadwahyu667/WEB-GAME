@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container section">
    {{-- Breadcrumb --}}
    <nav class="breadcrumb" style="margin-bottom: var(--space-xl); color: var(--text-secondary); font-size: 0.85rem;">
        <a href="{{ route('home') }}">Home</a> &gt; 
        <a href="{{ route('cart.index') }}">Keranjang</a> &gt; 
        <span>Checkout</span>
    </nav>

    <div style="margin-bottom: var(--space-2xl);">
        <h1 style="font-family: var(--font-heading); font-size: 2rem;">Checkout</h1>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: var(--space-2xl); align-items: start;">
            
            {{-- Left Column --}}
            <div>
                {{-- Data Pembeli --}}
                <div class="card" style="padding: var(--space-xl); margin-bottom: var(--space-lg); border-radius: var(--radius-lg);">
                    <h2 style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-flex; width: 24px; height: 24px; background: rgba(0, 229, 212, 0.1); color: var(--primary-cyan); border-radius: 50%; align-items: center; justify-content: center; font-size: 0.9rem;">1</span>
                        Data Pembeli
                    </h2>

                    <div style="margin-bottom: var(--space-md);">
                        <label for="customer_name" style="display: block; font-weight: 500; margin-bottom: 8px;">Nama Lengkap</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-input @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required style="width: 100%;">
                        @error('customer_name')
                            <span style="color: #EF4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: var(--space-md);">
                        <label for="customer_email" style="display: block; font-weight: 500; margin-bottom: 8px;">Email</label>
                        <input type="email" id="customer_email" name="customer_email" class="form-input @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required style="width: 100%;">
                        @error('customer_email')
                            <span style="color: #EF4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: var(--space-md);">
                        <label for="customer_whatsapp" style="display: block; font-weight: 500; margin-bottom: 8px;">Nomor WhatsApp</label>
                        <input type="text" id="customer_whatsapp" name="customer_whatsapp" class="form-input @error('customer_whatsapp') is-invalid @enderror" value="{{ old('customer_whatsapp', auth()->user()->phone ?? '') }}" required placeholder="Contoh: 081234567890" style="width: 100%;">
                        @error('customer_whatsapp')
                            <span style="color: #EF4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="card" style="padding: var(--space-xl); border-radius: var(--radius-lg);">
                    <h2 style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-flex; width: 24px; height: 24px; background: rgba(0, 229, 212, 0.1); color: var(--primary-cyan); border-radius: 50%; align-items: center; justify-content: center; font-size: 0.9rem;">2</span>
                        Metode Pembayaran
                    </h2>

                    <label class="card" style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); cursor: pointer; border: 2px solid var(--primary-cyan); background: rgba(0, 229, 212, 0.05);">
                        <input type="radio" name="payment_method" value="qris" checked style="accent-color: var(--primary-cyan);">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 4px;">QRIS</div>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">Mendukung semua aplikasi e-wallet dan m-banking</div>
                        </div>
                        <div style="font-size: 2rem;">📱</div>
                    </label>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 12px; display: flex; align-items: center; gap: 8px;">
                        <span style="color: var(--primary-cyan);">ℹ️</span> QR Code akan muncul setelah order dibuat.
                    </p>
                    @error('payment_method')
                        <span style="color: #EF4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Right Column (Summary) --}}
            <div>
                <div class="cart-summary card" style="padding: var(--space-xl); border-radius: var(--radius-lg); position: sticky; top: 100px;">
                    <h3 style="font-family: var(--font-heading); margin-bottom: var(--space-md); padding-bottom: var(--space-sm); border-bottom: 1px solid var(--border-color);">Ringkasan Pesanan</h3>
                    
                    <div style="margin-bottom: var(--space-lg); max-height: 250px; overflow-y: auto; padding-right: 8px;">
                        @php
                            $items = is_array($cart['items']) ? $cart['items'] : $cart->items;
                        @endphp
                        @foreach($items as $item)
                            @php
                                $product = $item->product ?? $item['product'];
                                $quantity = $item->quantity ?? $item['quantity'];
                            @endphp
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem;">
                                <div style="flex: 1; padding-right: 12px;">
                                    <div style="font-weight: 500; margin-bottom: 4px;">{{ $product->name }}</div>
                                    <div style="color: var(--text-secondary);">{{ $quantity }}x Rp {{ number_format($product->effective_price ?? $product->price, 0, ',', '.') }}</div>
                                </div>
                                <div style="font-weight: 600;">
                                    Rp {{ number_format(($product->effective_price ?? $product->price) * $quantity, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $summaryService = app(\App\Services\CartService::class);
                        $summary = $summaryService->getCartSummary();
                    @endphp

                    <div style="padding-top: var(--space-md); border-top: 1px dashed var(--border-color);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--text-secondary);">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        
                        @if($summary['discount'] > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; color: var(--primary-cyan);">
                            <span>Total Diskon</span>
                            <span>- Rp {{ number_format($summary['discount'], 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-xl); padding-top: var(--space-md); border-top: 1px solid var(--border-color); font-weight: 700; font-size: 1.25rem;">
                            <span>Total Bayar</span>
                            <span style="color: var(--primary-cyan);">Rp {{ number_format($summary['total'], 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" id="btn-submit" class="btn btn--primary" style="width: 100%; font-size: 1.1rem; padding: 14px;">
                            <span>Bayar Sekarang</span>
                            <span id="btn-spinner" style="display: none; margin-left: 8px;">⏳</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 380px"] { grid-template-columns: 1fr !important; }
    .cart-summary { position: static !important; margin-top: var(--space-lg); }
}
.is-invalid { border-color: #EF4444 !important; }
</style>

@push('scripts')
<script>
    document.getElementById('checkout-form').addEventListener('submit', function() {
        const btn = document.getElementById('btn-submit');
        const spinner = document.getElementById('btn-spinner');
        
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.cursor = 'not-allowed';
        spinner.style.display = 'inline-block';
    });
</script>
@endpush
@endsection
