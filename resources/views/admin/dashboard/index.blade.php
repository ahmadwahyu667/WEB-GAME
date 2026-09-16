@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-lg); margin-bottom: var(--space-xl);">
    <div class="admin-card" style="padding: var(--space-lg);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 6px;">Total Penjualan</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-cyan);">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(0,229,212,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="trending-up" style="color: var(--primary-cyan); width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>

    <div class="admin-card" style="padding: var(--space-lg);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 6px;">Total Pesanan</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ number_format($totalOrders ?? 0) }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(122,44,255,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="shopping-cart" style="color: var(--accent-purple); width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>

    <div class="admin-card" style="padding: var(--space-lg);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 6px;">Total Produk</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ number_format($totalProducts ?? 0) }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="package" style="color: #F59E0B; width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>

    <div class="admin-card" style="padding: var(--space-lg);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 6px;">Total Customer</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ number_format($totalCustomers ?? 0) }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(59,130,246,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="users" style="color: #3B82F6; width: 24px; height: 24px;"></i>
            </div>
        </div>
    </div>
</div>

{{-- Order Status Cards --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-lg); margin-bottom: var(--space-xl);">
    <div class="admin-card" style="padding: var(--space-lg); border-left: 3px solid #F59E0B;">
        <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 4px;">Menunggu Pembayaran</div>
        <div style="font-size: 1.25rem; font-weight: 700; color: #F59E0B;">{{ $pendingPayment ?? 0 }}</div>
    </div>
    <div class="admin-card" style="padding: var(--space-lg); border-left: 3px solid #3B82F6;">
        <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 4px;">Sudah Dibayar</div>
        <div style="font-size: 1.25rem; font-weight: 700; color: #3B82F6;">{{ $paidOrders ?? 0 }}</div>
    </div>
    <div class="admin-card" style="padding: var(--space-lg); border-left: 3px solid #10B981;">
        <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 4px;">Selesai</div>
        <div style="font-size: 1.25rem; font-weight: 700; color: #10B981;">{{ $completedOrders ?? 0 }}</div>
    </div>
</div>

{{-- Tables --}}
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-xl);">
    {{-- Recent Orders --}}
    <div class="admin-card">
        <div class="flex-between" style="margin-bottom: var(--space-lg);">
            <h3 style="font-weight: 600; color: var(--text-primary); font-size: 1rem;">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" style="color: var(--primary-cyan); font-size: 0.85rem; text-decoration: none;">Lihat Semua →</a>
        </div>
        <div class="admin-table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td style="font-size: 0.85rem;">#{{ $order->order_number }}</td>
                            <td style="font-size: 0.85rem;">{{ $order->user->name ?? 'Guest' }}</td>
                            <td style="font-size: 0.85rem; font-weight: 600;">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: var(--space-xl);">Belum ada pesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="admin-card">
        <div class="flex-between" style="margin-bottom: var(--space-lg);">
            <h3 style="font-weight: 600; color: var(--text-primary); font-size: 1rem;">Produk Terlaris</h3>
            <a href="{{ route('admin.products.index') }}" style="color: var(--primary-cyan); font-size: 0.85rem; text-decoration: none;">Lihat Semua →</a>
        </div>
        <div class="admin-table-container">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts ?? [] as $product)
                        <tr>
                            <td style="font-size: 0.85rem;">{{ $product->name }}</td>
                            <td style="font-size: 0.85rem; font-weight: 600; color: var(--primary-cyan);">{{ $product->order_items_count }} item</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center; color: var(--text-secondary); padding: var(--space-xl);">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Low Stock --}}
@if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
<div class="admin-card" style="margin-top: var(--space-xl);">
    <div class="flex-between" style="margin-bottom: var(--space-lg);">
        <h3 style="font-weight: 600; color: #EF4444; font-size: 1rem;">⚠️ Stok Hampir Habis</h3>
    </div>
    <div class="admin-table-container">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Game</th>
                    <th>Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $product)
                    <tr>
                        <td style="font-size: 0.85rem;">{{ $product->name }}</td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);">{{ $product->game->name ?? '-' }}</td>
                        <td>
                            <span style="display: inline-block; padding: 3px 10px; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 600; background: rgba(239,68,68,0.1); color: #EF4444;">{{ $product->stock }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
