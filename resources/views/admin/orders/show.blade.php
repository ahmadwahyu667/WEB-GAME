@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')

<div class="flex-between" style="margin-bottom: var(--space-xl);">
    <h2 style="font-weight: 700; color: var(--text-primary); font-size: 1.1rem;">Pesanan #{{ $order->order_number }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-admin-secondary">← Kembali</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl);">
    {{-- Order Details --}}
    <div>
        {{-- Items --}}
        <div class="admin-card">
            <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Item Pesanan</h3>
            <div class="admin-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items ?? [] as $item)
                            <tr>
                                <td style="font-size: 0.85rem;">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                <td style="font-size: 0.85rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td style="font-size: 0.85rem;">{{ $item->quantity }}</td>
                                <td style="font-size: 0.85rem; font-weight: 600;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: var(--space-xl);">Tidak ada item</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Payment Info --}}
        @if($order->payment)
        <div class="admin-card" style="margin-top: var(--space-xl);">
            <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Informasi Pembayaran</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-md);">
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Metode</div>
                    <div style="color: var(--text-primary);">{{ strtoupper($order->payment->payment_method ?? 'QRIS') }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Status Pembayaran</div>
                    <span class="badge badge-{{ $order->payment->status ?? 'pending' }}">{{ ucfirst($order->payment->status ?? 'pending') }}</span>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Reference ID</div>
                    <div style="color: var(--text-secondary); font-size: 0.85rem;">{{ $order->payment->reference_id ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Jumlah</div>
                    <div style="color: var(--primary-cyan); font-weight: 700;">Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div>
        {{-- Order Summary --}}
        <div class="admin-card">
            <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Ringkasan</h3>
            <div style="display: grid; gap: var(--space-md);">
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Status</div>
                    <span class="badge badge-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Customer</div>
                    <div style="color: var(--text-primary);">{{ $order->user->name ?? 'Guest' }}</div>
                    <div style="color: var(--text-secondary); font-size: 0.8rem;">{{ $order->user->email ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Subtotal</div>
                    <div style="color: var(--text-primary);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                </div>
                @if($order->discount > 0)
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Diskon</div>
                    <div style="color: #10B981;">- Rp {{ number_format($order->discount, 0, ',', '.') }}</div>
                </div>
                @endif
                <div style="border-top: 1px solid var(--border-color); padding-top: var(--space-md);">
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Total</div>
                    <div style="color: var(--primary-cyan); font-weight: 700; font-size: 1.25rem;">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 4px;">Tanggal Order</div>
                    <div style="color: var(--text-secondary); font-size: 0.85rem;">{{ $order->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        @if(!in_array($order->status, ['completed', 'cancelled']))
        <div class="admin-card" style="margin-top: var(--space-lg);">
            <h3 style="font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-lg);">Ubah Status</h3>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <select name="status" class="form-control">
                        @foreach(['pending', 'paid', 'processing', 'completed', 'failed', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%;" onclick="return confirm('Yakin ingin mengubah status pesanan?')">Update Status</button>
            </form>
        </div>
        @endif
    </div>
</div>

@endsection
