@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')

<div class="flex-between" style="margin-bottom: var(--space-xl);">
    <h2 style="font-weight: 700; color: var(--text-primary); font-size: 1.1rem;">{{ $product->name }}</h2>
    <div class="flex-gap">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-admin btn-admin-primary">
            <i data-lucide="edit" style="width: 16px; height: 16px;"></i> Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-secondary">← Kembali</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-xl);">
    {{-- Product Info --}}
    <div class="admin-card">
        <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Informasi Produk</h3>
        <div style="display: grid; gap: var(--space-md);">
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Game</div>
                <div style="color: var(--text-primary);">{{ $product->game->name ?? '-' }}</div>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Kategori</div>
                <div style="color: var(--text-primary);">{{ $product->category->name ?? '-' }}</div>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Harga</div>
                <div style="color: var(--primary-cyan); font-weight: 700; font-size: 1.1rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                @if($product->discount_price)
                    <div style="color: #10B981; font-size: 0.85rem;">Diskon: Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                @endif
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Stok</div>
                <div style="color: var(--text-primary); font-weight: 600;">{{ $product->stock }}</div>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Status</div>
                <span class="badge badge-{{ $product->status === 'active' ? 'active' : 'inactive' }}">{{ ucfirst($product->status) }}</span>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Tipe Pengiriman</div>
                <div style="color: var(--text-primary);">{{ ucfirst(str_replace('_', ' ', $product->delivery_type)) }}</div>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Terjual</div>
                <div style="color: var(--text-primary);">{{ $product->sold_count }} item</div>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Rating</div>
                <div style="color: #F59E0B;">★ {{ number_format($product->rating, 1) }}</div>
            </div>
        </div>
    </div>

    {{-- Description & Image --}}
    <div>
        <div class="admin-card" style="margin-bottom: var(--space-xl);">
            <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Gambar</h3>
            @if($product->images && $product->images->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-md);">
                    @foreach($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    @endforeach
                </div>
            @else
                <div style="color: var(--text-secondary); text-align: center; padding: var(--space-xl);">Belum ada gambar</div>
            @endif
        </div>

        <div class="admin-card">
            <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Deskripsi</h3>
            <div style="color: var(--text-secondary); line-height: 1.6; font-size: 0.9rem;">
                {{ $product->description ?? 'Belum ada deskripsi.' }}
            </div>
        </div>
    </div>
</div>

{{-- Recent Order Items --}}
<div class="admin-card" style="margin-top: var(--space-xl);">
    <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Pesanan Terbaru</h3>
    <div class="admin-table-container">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product->orderItems ?? [] as $item)
                    <tr>
                        <td style="font-size: 0.85rem;">#{{ $item->order->order_number ?? '-' }}</td>
                        <td style="font-size: 0.85rem;">{{ $item->quantity }}</td>
                        <td style="font-size: 0.85rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td><span class="badge badge-{{ $item->order->status ?? 'pending' }}">{{ ucfirst($item->order->status ?? 'pending') }}</span></td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);">{{ $item->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: var(--space-xl);">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
