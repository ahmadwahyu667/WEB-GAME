@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="section container">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: var(--space-lg); font-size: 0.9rem;">
        <a href="{{ url('/') }}" style="color: var(--text-secondary); text-decoration: none;">Home</a> 
        <span style="color: var(--text-muted); margin: 0 8px;">/</span>
        <a href="{{ route('orders.index') }}" style="color: var(--text-secondary); text-decoration: none;">Pesanan</a>
        <span style="color: var(--text-muted); margin: 0 8px;">/</span>
        <span style="color: var(--text-primary); font-weight: 600;">#{{ $order->order_number }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: var(--space-2xl); align-items: start;">
        
        {{-- Left Column --}}
        <div>
            {{-- Order Header --}}
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl); margin-bottom: var(--space-xl);">
                @php
                    $statusColors = [
                        'pending' => '#F59E0B',
                        'waiting_payment' => '#3B82F6',
                        'paid' => '#10B981',
                        'processing' => '#8B5CF6',
                        'completed' => '#00E5D4',
                        'cancelled' => '#EF4444',
                        'expired' => '#EF4444',
                        'failed' => '#EF4444'
                    ];
                    $statusColor = $statusColors[$order->status] ?? '#F59E0B';
                    $statusText = [
                        'pending' => 'Menunggu Pembayaran',
                        'waiting_payment' => 'Menunggu Pembayaran',
                        'paid' => 'Dibayar',
                        'processing' => 'Sedang Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'expired' => 'Kedaluwarsa',
                        'failed' => 'Gagal'
                    ][$order->status] ?? ucfirst($order->status);
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-md);">
                    <h1 style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0;">#{{ $order->order_number }}</h1>
                    <span style="padding: 6px 16px; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600; color: {{ $statusColor }}; background: rgba(255,255,255,0.05); border: 1px solid {{ $statusColor }};">
                        {{ $statusText }}
                    </span>
                </div>
                <div style="color: var(--text-secondary); font-size: 0.9rem;">
                    Tanggal Pemesanan: {{ $order->created_at->format('d M Y, H:i') }}
                </div>
            </div>

            {{-- Order Items --}}
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl); margin-bottom: var(--space-xl);">
                <h2 style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-lg);">Detail Produk</h2>
                
                <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                    @foreach($order->items as $item)
                        <div style="display: flex; gap: var(--space-md); padding-bottom: var(--space-md); border-bottom: 1px solid var(--border-color);">
                            <img src="{{ optional($item->product)->image ?? 'https://via.placeholder.com/80' }}" alt="{{ $item->product_name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-sm);">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">{{ $item->product_name }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px;">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</div>
                            </div>
                            <div style="font-weight: 700; color: var(--text-primary);">
                                Rp {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Customer Info --}}
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl);">
                <h2 style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-lg);">Informasi Pelanggan</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
                    <div>
                        <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 4px;">Nama</div>
                        <div style="color: var(--text-primary); font-weight: 500;">{{ optional($order->user)->name ?? 'Guest' }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 4px;">Email</div>
                        <div style="color: var(--text-primary); font-weight: 500;">{{ optional($order->user)->email ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            {{-- Status Timeline --}}
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl); margin-bottom: var(--space-xl);">
                <h2 style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-lg);">Status Pesanan</h2>
                
                @php
                    // Determine which steps are active based on status
                    $isPending = in_array($order->status, ['pending', 'waiting_payment']);
                    $isPaid = in_array($order->status, ['paid', 'processing', 'completed']);
                    $isProcessing = in_array($order->status, ['processing', 'completed']);
                    $isCompleted = $order->status === 'completed';
                    $isFailed = in_array($order->status, ['cancelled', 'expired', 'failed']);
                @endphp

                <div style="position: relative; padding-left: 24px;">
                    <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background: var(--border-color);"></div>

                    {{-- Step 1 --}}
                    <div style="position: relative; margin-bottom: var(--space-lg);">
                        <div style="position: absolute; left: -24px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: var(--primary-cyan); box-shadow: 0 0 0 4px var(--bg-card);"></div>
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">Pesanan Dibuat</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>

                    @if($isFailed)
                        <div style="position: relative;">
                            <div style="position: absolute; left: -24px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: #EF4444; box-shadow: 0 0 0 4px var(--bg-card);"></div>
                            <div style="font-weight: 600; color: #EF4444; font-size: 0.95rem;">{{ $statusText }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Pesanan tidak dapat dilanjutkan.</div>
                        </div>
                    @else
                        {{-- Step 2 --}}
                        <div style="position: relative; margin-bottom: var(--space-lg);">
                            <div style="position: absolute; left: -24px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: {{ $isPaid ? 'var(--primary-cyan)' : ($isPending ? '#F59E0B' : 'var(--border-color)') }}; box-shadow: 0 0 0 4px var(--bg-card);"></div>
                            <div style="font-weight: 600; color: {{ $isPaid || $isPending ? 'var(--text-primary)' : 'var(--text-secondary)' }}; font-size: 0.95rem;">Menunggu Pembayaran</div>
                        </div>

                        {{-- Step 3 --}}
                        <div style="position: relative; margin-bottom: var(--space-lg);">
                            <div style="position: absolute; left: -24px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: {{ $isProcessing ? 'var(--primary-cyan)' : ($isPaid && !$isProcessing ? '#3B82F6' : 'var(--border-color)') }}; box-shadow: 0 0 0 4px var(--bg-card);"></div>
                            <div style="font-weight: 600; color: {{ $isProcessing || $isPaid ? 'var(--text-primary)' : 'var(--text-secondary)' }}; font-size: 0.95rem;">Pembayaran Berhasil</div>
                            @if($order->paid_at)
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $order->paid_at->format('d M Y, H:i') }}</div>
                            @endif
                        </div>

                        {{-- Step 4 --}}
                        <div style="position: relative; margin-bottom: var(--space-lg);">
                            <div style="position: absolute; left: -24px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: {{ $isCompleted ? 'var(--primary-cyan)' : ($isProcessing && !$isCompleted ? '#8B5CF6' : 'var(--border-color)') }}; box-shadow: 0 0 0 4px var(--bg-card);"></div>
                            <div style="font-weight: 600; color: {{ $isCompleted || $isProcessing ? 'var(--text-primary)' : 'var(--text-secondary)' }}; font-size: 0.95rem;">Sedang Diproses</div>
                        </div>

                        {{-- Step 5 --}}
                        <div style="position: relative;">
                            <div style="position: absolute; left: -24px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: {{ $isCompleted ? 'var(--primary-cyan)' : 'var(--border-color)' }}; box-shadow: 0 0 0 4px var(--bg-card);"></div>
                            <div style="font-weight: 600; color: {{ $isCompleted ? 'var(--text-primary)' : 'var(--text-secondary)' }}; font-size: 0.95rem;">Selesai</div>
                            @if($order->completed_at)
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $order->completed_at->format('d M Y, H:i') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Payment & Total --}}
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl);">
                <h2 style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-lg);">Ringkasan Pembayaran</h2>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                    <span style="color: var(--text-secondary);">Metode Pembayaran</span>
                    <span style="color: var(--text-primary); font-weight: 500;">{{ optional($order->payment)->payment_method ?? 'QRIS' }}</span>
                </div>
                
                <hr style="border: 0; border-top: 1px dashed var(--border-color); margin: var(--space-md) 0;">

                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                    <span style="color: var(--text-secondary);">Subtotal</span>
                    <span style="color: var(--text-primary);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                    <span style="color: var(--text-secondary);">Diskon</span>
                    <span style="color: var(--primary-cyan);">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 12px 0;">
                
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.2rem; color: var(--text-primary); margin-bottom: var(--space-xl);">
                    <span>Total</span>
                    <span style="color: var(--primary-cyan);">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>

                {{-- Action Buttons --}}
                @if($isPending)
                    <a href="{{ route('payment.show', $order->id) }}" style="display: block; text-align: center; width: 100%; padding: 12px; background: var(--gradient-primary); color: var(--bg-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; margin-bottom: var(--space-md);">
                        Lanjutkan Pembayaran
                    </a>
                    <form action="{{ route('payment.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan?');">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 12px; background: transparent; border: 1px solid #EF4444; color: #EF4444; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; transition: background 0.3s;">
                            Batalkan Pesanan
                        </button>
                    </form>
                @elseif($isCompleted)
                    <a href="{{ url('/') }}" style="display: block; text-align: center; width: 100%; padding: 12px; background: var(--accent-purple); color: var(--text-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none;">
                        Beli Lagi
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
