@extends('layouts.admin')

@section('title', 'Mock Payment Testing')

@section('content')
<div class="admin-card" style="background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3); margin-bottom: var(--space-xl);">
    <div style="padding: 15px; color: #F59E0B; display: flex; align-items: flex-start; gap: 15px;">
        <i data-lucide="alert-triangle" style="margin-top: 3px; width: 24px; height: 24px;"></i>
        <div>
            <h4 style="margin: 0 0 5px 0; font-size: 1.05rem; font-weight: 700;">Hanya Mode Development (Mock Mode)</h4>
            <p style="margin: 0; font-size: 0.9rem; color: var(--text-secondary);">Fitur ini digunakan untuk mensimulasikan callback status pembayaran QRIS secara instan tanpa perlu transaksi riil.</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="flex-between" style="margin-bottom: var(--space-lg);">
        <h2 style="font-weight: 600; color: var(--text-primary); font-size: 1rem;">Menunggu Pembayaran (Pending Payments)</h2>
    </div>

    <div class="admin-table-container">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Reference ID</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Dibuat</th>
                    <th style="text-align: center;">Trigger Simulasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingPayments as $payment)
                    <tr>
                        <td style="font-weight: 500; font-size: 0.85rem;">
                            @if($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order->id) }}" style="color: var(--primary-cyan); text-decoration: none;">{{ $payment->order->order_number }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary); font-family: monospace;">{{ $payment->reference_id }}</td>
                        <td style="font-size: 0.85rem;">{{ strtoupper($payment->payment_method) }}</td>
                        <td style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);">{{ $payment->created_at->format('d M Y H:i') }}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <form action="{{ route('admin.mockPayment.trigger') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" class="btn-admin" style="background: #10B981; color: white; padding: 6px 12px; font-size: 0.8rem;" title="Trigger Berhasil (Paid)">
                                        <i data-lucide="check" style="width: 14px; height: 14px;"></i> Sukses
                                    </button>
                                </form>

                                <form action="{{ route('admin.mockPayment.trigger') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                    <input type="hidden" name="status" value="expired">
                                    <button type="submit" class="btn-admin" style="background: #F59E0B; color: white; padding: 6px 12px; font-size: 0.8rem;" title="Trigger Kedaluwarsa (Expired)">
                                        <i data-lucide="clock" style="width: 14px; height: 14px;"></i> Expired
                                    </button>
                                </form>

                                <form action="{{ route('admin.mockPayment.trigger') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                    <input type="hidden" name="status" value="failed">
                                    <button type="submit" class="btn-admin" style="background: #EF4444; color: white; padding: 6px 12px; font-size: 0.8rem;" title="Trigger Gagal (Failed)">
                                        <i data-lucide="x" style="width: 14px; height: 14px;"></i> Gagal
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: var(--space-2xl); color: var(--text-secondary);">
                            <i data-lucide="check-circle" style="width: 40px; height: 40px; color: var(--text-muted); margin: 0 auto var(--space-md); display: block;"></i>
                            Tidak ada pembayaran pending saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendingPayments->hasPages())
        <div style="padding: var(--space-lg); border-top: 1px solid var(--border-color);">
            {{ $pendingPayments->links() }}
        </div>
    @endif
</div>
@endsection
