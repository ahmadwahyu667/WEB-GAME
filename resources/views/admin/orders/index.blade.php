@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')

<div class="flex-between" style="margin-bottom: var(--space-xl);">
    <h2 style="font-weight: 700; color: var(--text-primary); font-size: 1.1rem;">Daftar Pesanan</h2>
</div>

{{-- Filter --}}
<div class="admin-card" style="margin-bottom: var(--space-xl); padding: var(--space-lg);">
    <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: var(--space-md); align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label class="form-label">Cari Order</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nomor pesanan...">
        </div>
        <div class="form-group" style="min-width: 150px; margin-bottom: 0;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                @foreach(['pending', 'waiting_payment', 'paid', 'processing', 'completed', 'cancelled', 'expired', 'failed'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: var(--space-sm);">
            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 10px 20px;">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-admin-secondary" style="padding: 10px 20px;">Reset</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div class="admin-table-container">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-weight: 500; font-size: 0.85rem;">{{ $order->order_number }}</td>
                        <td style="font-size: 0.85rem;">{{ $order->user->name ?? 'Guest' }}</td>
                        <td style="font-weight: 600; font-size: 0.85rem;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td><span class="badge badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-icon" title="Detail">
                                <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: var(--space-2xl);">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div style="padding: var(--space-lg); border-top: 1px solid var(--border-color);">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection
