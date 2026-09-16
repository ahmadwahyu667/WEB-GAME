@extends('layouts.app')

@section('content')
<section class="section" style="padding-top: 120px; min-height: 80vh;">
    <div class="container">
        @include('components.breadcrumb', [
            'crumbs' => [
                ['label' => 'Tentang Kami', 'url' => url('/about')]
            ]
        ])
        
        <div class="section-title text-center" style="margin-bottom: 48px;">
            <h2>Tentang <span class="accent">GameMarket</span></h2>
            <p>Marketplace top-up game terbaik dan terpercaya di Indonesia.</p>
        </div>
        
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-2xl); max-width: 900px; margin: 0 auto;">
            <h3 style="font-family: var(--font-heading); color: var(--text-primary); margin-bottom: 16px; font-size: 1.5rem;">Misi Kami</h3>
            <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: 32px;">
                GameMarket Indonesia didirikan pada tahun 2023 dengan misi utama: memberikan kemudahan, kecepatan, dan keamanan bagi setiap gamer di Indonesia dalam memenuhi kebutuhan digital mereka. Kami percaya bahwa pengalaman bermain game tidak boleh terhambat oleh proses pembelian item atau top-up yang rumit dan lambat.
            </p>
            
            <h3 style="font-family: var(--font-heading); color: var(--text-primary); margin-bottom: 16px; font-size: 1.5rem;">Mengapa Memilih Kami?</h3>
            <ul style="color: var(--text-secondary); line-height: 1.8; margin-bottom: 32px; padding-left: 20px;">
                <li style="margin-bottom: 8px;"><strong style="color: var(--text-primary);">Harga Terbaik:</strong> Kami selalu berusaha memberikan harga paling kompetitif di pasar.</li>
                <li style="margin-bottom: 8px;"><strong style="color: var(--text-primary);">Proses Otomatis 24/7:</strong> Sistem kami memproses pesanan Anda secara otomatis kapan pun Anda bertransaksi.</li>
                <li style="margin-bottom: 8px;"><strong style="color: var(--text-primary);">Metode Pembayaran Lengkap:</strong> Didukung berbagai channel pembayaran, dari QRIS, E-Wallet, hingga Bank Transfer.</li>
                <li style="margin-bottom: 8px;"><strong style="color: var(--text-primary);">Customer Support Responsif:</strong> Tim kami siap membantu menyelesaikan segala kendala Anda.</li>
            </ul>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 48px; border-top: 1px solid var(--border-color); padding-top: 32px; text-align: center;">
                <div>
                    <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: bold; color: var(--primary-cyan);">500+</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">Produk Tersedia</div>
                </div>
                <div>
                    <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: bold; color: var(--primary-cyan);">1M+</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">Transaksi Berhasil</div>
                </div>
                <div>
                    <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: bold; color: var(--primary-cyan);">24/7</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">Layanan Pelanggan</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
