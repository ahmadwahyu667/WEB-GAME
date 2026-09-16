@extends('layouts.app')
@section('title', 'Pembayaran QRIS')

@section('content')
<div class="section" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: var(--space-2xl); width: 100%; max-width: 600px; text-align: center; position: relative;">
        
        <h1 style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; margin-bottom: var(--space-xl); background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-transform: uppercase;">Pembayaran QRIS</h1>

        <div style="margin-bottom: var(--space-md); color: var(--text-secondary); font-size: 0.9rem;">
            Order #{{ $order->order_number ?? 'GM-20260916-000001' }}
        </div>

        <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">
            Rp {{ number_format($payment->amount ?? $order->total ?? 0, 0, ',', '.') }}
        </div>

        {{-- Status Badge --}}
        @php
            $status = $payment->status ?? 'pending';
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
            $color = $statusColors[$status] ?? '#F59E0B';
            $statusText = [
                'pending' => 'Menunggu Pembayaran',
                'paid' => 'Sudah Dibayar',
                'expired' => 'Kedaluwarsa',
                'failed' => 'Gagal',
                'cancelled' => 'Dibatalkan'
            ][$status] ?? ucfirst($status);
        @endphp
        
        <div style="margin-bottom: var(--space-xl);">
            <span style="display: inline-block; padding: 6px 16px; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600; color: {{ $color }}; background: rgba(255,255,255,0.05); border: 1px solid {{ $color }};">
                {{ $statusText }}
            </span>
        </div>

        @if(in_array($status, ['pending', 'waiting_payment']))
            {{-- QR Code Box --}}
            <div style="background: white; padding: var(--space-lg); border-radius: var(--radius-md); display: inline-block; margin-bottom: var(--space-lg);">
                @if(isset($payment->qr_code) && str_starts_with($payment->qr_code, 'http'))
                    <img src="{{ $payment->qr_code }}" alt="QRIS" style="width: 250px; height: 250px; object-fit: contain;">
                @elseif(isset($payment->qr_code) && str_starts_with($payment->qr_code, 'data:image'))
                    <img src="{{ $payment->qr_code }}" alt="QRIS" style="width: 250px; height: 250px; object-fit: contain;">
                @else
                    <div style="width: 250px; height: 250px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #333; font-weight: 600; flex-direction: column;">
                        <div>QRIS Reference</div>
                        <div style="font-size: 0.8rem; margin-top: 8px;">{{ $payment->reference_id ?? 'QR_MOCK_123' }}</div>
                    </div>
                @endif
            </div>

            {{-- Timer --}}
            <div style="margin-bottom: var(--space-xl);">
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 8px;">Sisa Waktu Pembayaran</div>
                <div id="countdown-timer" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-cyan); font-family: monospace;">
                    --:--
                </div>
            </div>

            {{-- Actions --}}
            <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                <button type="button" onclick="checkPaymentStatus()" style="width: 100%; padding: 12px; background: var(--gradient-primary); color: var(--bg-primary); border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; transition: opacity 0.3s;">
                    Sudah Membayar? Cek Status
                </button>
                
                <form action="{{ route('payment.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 12px; background: transparent; color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-weight: 600; cursor: pointer; transition: color 0.3s;">
                        Batalkan
                    </button>
                </form>
            </div>
        @elseif(in_array($status, ['expired', 'failed', 'cancelled']))
            <div style="margin: var(--space-2xl) 0;">
                <div style="color: var(--text-primary); font-size: 1.1rem; margin-bottom: var(--space-md);">Pembayaran Kedaluwarsa / Gagal</div>
                <a href="{{ route('orders.show', $order->id) }}" style="display: inline-block; padding: 12px 24px; background: var(--primary-cyan); color: var(--bg-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none;">Lihat Pesanan</a>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    @if(in_array($payment->status ?? 'pending', ['pending', 'waiting_payment']))
        const expiryDate = new Date("{{ $payment->expired_at ? $payment->expired_at->toIso8601String() : now()->addMinutes(15)->toIso8601String() }}").getTime();
        
        function updateTimer() {
            const now = new Date().getTime();
            const distance = expiryDate - now;

            if (distance < 0) {
                document.getElementById('countdown-timer').innerHTML = "00:00";
                window.location.reload();
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('countdown-timer').innerHTML = 
                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                (seconds < 10 ? "0" + seconds : seconds);
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // Polling status
        function checkPaymentStatus() {
            fetch(`/payment/{{ $order->id }}/check`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.status === 'paid') {
                        window.location.href = `/payment/${data.order_id}/success`;
                    } else if (['expired', 'failed', 'cancelled'].includes(data.status)) {
                        window.location.href = `/payment/${data.order_id}/failed`;
                    }
                }
            })
            .catch(error => console.error('Error checking payment status:', error));
        }

        setInterval(checkPaymentStatus, 5000);
    @endif
</script>
@endpush
