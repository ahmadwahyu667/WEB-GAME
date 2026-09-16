@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')

@push('styles')
<style>
    @keyframes checkmark {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }
    .check-icon-container {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-xl);
        animation: checkmark 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    .check-icon {
        color: var(--bg-primary);
        width: 40px;
        height: 40px;
    }
    
    .confetti-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: 10;
    }
    
    .confetti {
        position: absolute;
        width: 8px;
        height: 8px;
        background-color: var(--primary-cyan);
        opacity: 0;
    }
    
    @keyframes fall {
        0% { opacity: 1; transform: translateY(-10px) rotate(0deg); }
        100% { opacity: 0; transform: translateY(200px) rotate(360deg); }
    }
    
    @media (prefers-reduced-motion: no-preference) {
        .confetti {
            animation: fall 2s linear forwards;
        }
    }
    
    .success-card {
        animation: fadeInUp 0.6s ease;
    }
</style>
@endpush

@section('content')
<div class="section" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; position: relative;">
    
    <div class="confetti-container" id="confetti"></div>

    <div class="success-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: var(--space-3xl) var(--space-2xl); width: 100%; max-width: 560px; text-align: center; box-shadow: var(--shadow-glow-cyan); position: relative; z-index: 2;">
        
        <div class="check-icon-container">
            <svg class="check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 800; margin-bottom: var(--space-md); color: var(--primary-cyan);">Pembayaran Berhasil!</h1>

        <div style="margin-bottom: var(--space-xl); color: var(--text-secondary); font-size: 0.95rem;">
            Order #{{ $order->order_number ?? 'GM-XXXXXX' }}
        </div>

        <div style="background: var(--bg-secondary); border-radius: var(--radius-lg); padding: var(--space-md); margin-bottom: var(--space-xl); text-align: left; border: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                <span style="color: var(--text-secondary);">Subtotal</span>
                <span>Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
            </div>
            @if(($order->discount ?? 0) > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                <span style="color: var(--text-secondary);">Diskon</span>
                <span style="color: var(--primary-cyan);">-Rp {{ number_format($order->discount ?? 0, 0, ',', '.') }}</span>
            </div>
            @endif
            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 12px 0;">
            <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem; color: var(--text-primary);">
                <span>Total</span>
                <span>Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <a href="{{ route('orders.show', $order->id) }}" style="flex: 1; padding: 12px; background: var(--gradient-primary); color: var(--bg-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; transition: opacity 0.3s;">
                Lihat Pesanan
            </a>
            <a href="{{ url('/') }}" style="flex: 1; padding: 12px; background: transparent; border: 1px solid var(--border-color); color: var(--text-secondary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; transition: color 0.3s;">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const colors = ['#00E5D4', '#7A2CFF', '#FFFFFF', '#10B981'];
        const container = document.getElementById('confetti');
        
        for (let i = 0; i < 40; i++) {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animationDelay = Math.random() * 0.5 + 's';
            confetti.style.animationDuration = Math.random() * 1 + 1 + 's';
            
            // Randomize shape sometimes
            if(Math.random() > 0.5) confetti.style.borderRadius = '50%';
            
            container.appendChild(confetti);
        }
    });
</script>
@endpush
