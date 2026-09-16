@extends('layouts.app')
@section('title', 'Pembayaran Gagal')

@section('content')
<div class="section" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: var(--space-3xl) var(--space-2xl); width: 100%; max-width: 500px; text-align: center; position: relative;">
        
        <div style="width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); border: 2px solid #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-xl);">
            <svg style="color: #EF4444; width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>

        <h1 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 800; margin-bottom: var(--space-sm); color: #EF4444;">Pembayaran Gagal / Kedaluwarsa</h1>

        <div style="margin-bottom: var(--space-xl); color: var(--text-secondary); font-size: 0.95rem;">
            Order #{{ $order->order_number ?? 'GM-XXXXXX' }}
        </div>

        <p style="color: var(--text-secondary); margin-bottom: var(--space-2xl); font-size: 0.9rem; line-height: 1.6;">
            Mohon maaf, waktu pembayaran untuk pesanan ini telah habis atau transaksi dibatalkan. Silakan buat pesanan baru atau coba lagi.
        </p>

        <div style="display: flex; flex-direction: column; gap: var(--space-md);">
            <a href="{{ route('orders.show', $order->id) }}" style="width: 100%; padding: 12px; background: var(--gradient-primary); color: var(--bg-primary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; transition: opacity 0.3s;">
                Lihat Pesanan
            </a>
            <a href="{{ url('/') }}" style="width: 100%; padding: 12px; background: transparent; border: 1px solid var(--border-color); color: var(--text-secondary); border-radius: var(--radius-md); font-weight: 600; text-decoration: none; transition: color 0.3s;">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
