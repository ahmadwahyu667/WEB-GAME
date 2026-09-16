@extends('layouts.app')
@section('title', 'Riwayat Pesanan')

@section('content')
<div class="section container">
    <div style="margin-bottom: var(--space-2xl);">
        <h1 style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: var(--text-primary); margin-bottom: var(--space-md);">Riwayat Pesanan</h1>
        
        {{-- Search and Filters --}}
        <div style="display: flex; flex-wrap: wrap; gap: var(--space-md); justify-content: space-between; align-items: center;">
            
            {{-- Tabs --}}
            <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px;">
                @php
                    $currentStatus = request('status', '');
                    $tabs = [
                        '' => 'Semua',
                        'waiting_payment' => 'Menunggu Pembayaran',
                        'paid' => 'Dibayar',
                        'processing' => 'Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan'
                    ];
                @endphp
                
                @foreach($tabs as $val => $label)
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['status' => $val])) }}" 
                       style="padding: 8px 16px; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600; white-space: nowrap; 
                       @if($currentStatus === $val) background: var(--primary-cyan); color: var(--bg-primary); 
                       @else background: var(--bg-card); color: var(--text-secondary); border: 1px solid var(--border-color); @endif">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Search --}}
            <form action="{{ route('orders.index') }}" method="GET" style="display: flex; min-width: 250px;">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari No. Pesanan..." style="flex: 1; padding: 8px 16px; border-radius: var(--radius-full) 0 0 var(--radius-full); border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); font-size: 0.9rem; outline: none;">
                <button type="submit" style="padding: 8px 16px; border-radius: 0 var(--radius-full) var(--radius-full) 0; background: var(--primary-cyan); color: var(--bg-primary); border: none; font-weight: 600; cursor: pointer;">Cari</button>
            </form>
        </div>
    </div>

    {{-- Order List --}}
    @if($orders->count() > 0)
        <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
            @foreach($orders as $order)
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
                        'processing' => 'Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'expired' => 'Kedaluwarsa',
                        'failed' => 'Gagal'
                    ][$order->status] ?? ucfirst($order->status);
                    
                    $firstItem = $order->items->first();
                    $itemCount = $order->items->count();
                @endphp

                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl); transition: transform 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-md); flex-wrap: wrap; gap: var(--space-md);">
                        <div>
                            <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 4px;">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 1.1rem; font-family: monospace;">
                                {{ $order->order_number }}
                            </div>
                        </div>
                        <span style="padding: 4px 12px; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 600; color: {{ $statusColor }}; background: rgba(255,255,255,0.05); border: 1px solid {{ $statusColor }};">
                            {{ $statusText }}
                        </span>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: var(--space-md) 0;">

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-lg);">
                        <div style="flex: 1; min-width: 250px; display: flex; align-items: center; gap: var(--space-md);">
                            @if($firstItem && $firstItem->product)
                                <img src="{{ $firstItem->product->image ?? 'https://via.placeholder.com/60' }}" alt="Product" style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm);">
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary);">{{ $firstItem->product_name }}</div>
                                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                        {{ $firstItem->quantity }} x Rp {{ number_format($firstItem->price, 0, ',', '.') }}
                                        @if($itemCount > 1)
                                            <span style="color: var(--primary-cyan); font-weight: 600; margin-left: 8px;">+ {{ $itemCount - 1 }} item lainnya</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div style="color: var(--text-secondary); font-style: italic;">Item tidak ditemukan</div>
                            @endif
                        </div>

                        <div style="text-align: right;">
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">Total Belanja</div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 1.2rem;">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div style="margin-top: var(--space-lg); display: flex; justify-content: flex-end; gap: var(--space-md);">
                        <a href="{{ route('orders.show', $order->id) }}" style="padding: 8px 16px; border: 1px solid var(--border-color); color: var(--text-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                            Lihat Detail
                        </a>
                        
                        @if(in_array($order->status, ['pending', 'waiting_payment']))
                            <a href="{{ route('payment.show', $order->id) }}" style="padding: 8px 16px; background: var(--primary-cyan); color: var(--bg-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                                Bayar Sekarang
                            </a>
                        @elseif($order->status === 'completed' && $firstItem && $firstItem->product)
                            {{-- Ideally goes to product page, but we'll link to detail for now or a generic URL if not available --}}
                            <a href="{{ url('/') }}" style="padding: 8px 16px; background: var(--accent-purple); color: var(--text-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                                Beli Lagi
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: var(--space-2xl);">
            {{ $orders->links('pagination::tailwind') }}
        </div>
    @else
        <div style="text-align: center; padding: var(--space-3xl) 0; background: var(--bg-card); border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
            <div style="font-size: 3rem; margin-bottom: var(--space-md);">📦</div>
            <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Belum ada pesanan</h3>
            <p style="color: var(--text-secondary); margin-bottom: var(--space-lg);">Anda belum membuat pesanan yang sesuai dengan filter ini.</p>
            <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 24px; background: var(--gradient-primary); color: var(--bg-primary); border-radius: var(--radius-full); font-weight: 600; text-decoration: none;">Belanja Sekarang</a>
        </div>
    @endif
</div>
@endsection
