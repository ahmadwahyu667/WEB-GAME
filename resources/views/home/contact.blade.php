@extends('layouts.app')

@section('content')
<section class="section" style="padding-top: 120px; min-height: 80vh;">
    <div class="container">
        @include('components.breadcrumb', [
            'crumbs' => [
                ['label' => 'Kontak', 'url' => url('/contact')]
            ]
        ])
        
        <div class="section-title text-center" style="margin-bottom: 48px;">
            <h2>Hubungi <span class="accent">Kami</span></h2>
            <p>Punya pertanyaan atau kendala? Jangan ragu untuk menghubungi tim kami.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 48px; max-width: 1000px; margin: 0 auto;">
            
            <!-- Contact Info -->
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl); display: flex; flex-direction: column; gap: 24px;">
                <h3 style="font-family: var(--font-heading); color: var(--text-primary); margin-bottom: 8px;">Informasi Kontak</h3>
                
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="width: 40px; height: 40px; background: rgba(0, 229, 212, 0.1); color: var(--primary-cyan); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">📍</div>
                    <div>
                        <h4 style="color: var(--text-primary); margin-bottom: 4px; font-size: 0.9rem;">Alamat</h4>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5;">Gedung Cyber, Lt 5<br>Jl. Kuningan Barat No. 8<br>Jakarta Selatan, 12710</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="width: 40px; height: 40px; background: rgba(0, 229, 212, 0.1); color: var(--primary-cyan); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">📧</div>
                    <div>
                        <h4 style="color: var(--text-primary); margin-bottom: 4px; font-size: 0.9rem;">Email</h4>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5;">support@gamemarket.id</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="width: 40px; height: 40px; background: rgba(0, 229, 212, 0.1); color: var(--primary-cyan); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">📱</div>
                    <div>
                        <h4 style="color: var(--text-primary); margin-bottom: 4px; font-size: 0.9rem;">WhatsApp</h4>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5;">+62 812 3456 7890</p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-xl);">
                <h3 style="font-family: var(--font-heading); color: var(--text-primary); margin-bottom: 24px;">Kirim Pesan</h3>
                
                <form action="#" method="POST" onsubmit="alert('Pesan berhasil dikirim! Tim kami akan membalas segera.'); event.preventDefault(); this.reset();">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-input" required placeholder="Masukkan nama">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" required placeholder="Masukkan email">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <select name="subject" class="form-select" required>
                            <option value="">Pilih Subjek</option>
                            <option value="Tanya Produk">Tanya Produk</option>
                            <option value="Kendala Transaksi">Kendala Transaksi</option>
                            <option value="Kerjasama">Kerjasama</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Pesan</label>
                        <textarea name="message" class="form-textarea" required placeholder="Tuliskan pesan Anda di sini..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim Pesan Sekarang</button>
                </form>
            </div>
            
        </div>
    </div>
</section>
@endsection
