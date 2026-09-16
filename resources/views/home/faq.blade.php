@extends('layouts.app')

@section('content')
<section class="section" style="padding-top: 120px; min-height: 80vh;">
    <div class="container">
        @include('components.breadcrumb', [
            'crumbs' => [
                ['label' => 'FAQ', 'url' => url('/faq')]
            ]
        ])
        
        <div class="section-title text-center" style="margin-bottom: 48px;">
            <h2>Pertanyaan yang Sering <span class="accent">Diajukan</span></h2>
            <p>Temukan jawaban untuk pertanyaan umum tentang layanan GameMarket.</p>
        </div>
        
        <div class="faq-container" style="max-width: 800px; margin: 0 auto;">
            <!-- Dummy accordion CSS styles since app.css doesn't seem to have accordion styles explicitly defined -->
            <style>
                .faq-item {
                    background: var(--bg-card);
                    border: 1px solid var(--border-color);
                    border-radius: var(--radius-md);
                    margin-bottom: 16px;
                    overflow: hidden;
                }
                .faq-question {
                    padding: 20px;
                    font-family: var(--font-heading);
                    font-weight: 600;
                    cursor: pointer;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    color: var(--text-primary);
                }
                .faq-question:hover {
                    background: rgba(255,255,255,0.02);
                }
                .faq-answer {
                    padding: 0 20px 20px;
                    color: var(--text-secondary);
                    display: none;
                    line-height: 1.6;
                }
                .faq-item.active .faq-answer {
                    display: block;
                }
                .faq-icon {
                    transition: transform var(--transition-fast);
                }
                .faq-item.active .faq-icon {
                    transform: rotate(180deg);
                }
            </style>

            @php
                $faqs = [
                    [
                        'id' => 'cara-belanja',
                        'q' => 'Bagaimana cara melakukan pembelian di GameMarket?',
                        'a' => 'Pilih game atau item yang Anda inginkan, masukkan ID Game atau informasi yang diminta (jika ada), pilih nominal/paket, lalu klik Beli. Setelah itu pilih metode pembayaran dan selesaikan pembayaran. Item akan otomatis masuk ke akun game Anda.'
                    ],
                    [
                        'id' => 'cara-pembayaran',
                        'q' => 'Metode pembayaran apa saja yang tersedia?',
                        'a' => 'Kami mendukung pembayaran instan menggunakan QRIS (BCA, Mandiri, BNI, BRI, OVO, Dana, GoPay, ShopeePay, dll). Selain itu kami juga mendukung transfer bank dan Virtual Account.'
                    ],
                    [
                        'id' => 'waktu-proses',
                        'q' => 'Berapa lama proses top-up atau pengiriman item?',
                        'a' => 'Transaksi diproses secara otomatis dalam hitungan detik setelah pembayaran Anda berhasil dikonfirmasi oleh sistem. Dalam beberapa kasus, proses bisa memakan waktu maksimal 5-10 menit.'
                    ],
                    [
                        'id' => 'salah-id',
                        'q' => 'Apa yang terjadi jika saya salah memasukkan ID Game?',
                        'a' => 'Jika kesalahan ID menyebabkan transaksi gagal di sistem game, saldo Anda akan direfund ke akun GameMarket Anda. Namun jika ID tujuan valid dan item sudah terkirim, kami tidak dapat melakukan refund. Pastikan Anda memeriksa ulang ID sebelum membayar.'
                    ],
                    [
                        'id' => 'refund',
                        'q' => 'Apakah saya bisa meminta refund?',
                        'a' => 'Refund hanya dapat dilakukan apabila transaksi gagal atau stok produk kosong setelah Anda membayar. Refund akan diproses ke saldo GameMarket atau rekening bank Anda maksimal 2x24 jam.'
                    ],
                    [
                        'id' => 'aman',
                        'q' => 'Apakah aman bertransaksi di GameMarket?',
                        'a' => 'Sangat aman! Kami menggunakan sistem enkripsi dan bekerja sama langsung dengan penyedia payment gateway resmi. GameMarket tidak pernah menyimpan detail informasi bank Anda.'
                    ],
                    [
                        'id' => 'akun-wajib',
                        'q' => 'Apakah saya harus membuat akun untuk berbelanja?',
                        'a' => 'Anda bisa berbelanja tanpa membuat akun (Guest Checkout) untuk beberapa produk, namun kami sangat menyarankan Anda membuat akun untuk mempermudah pelacakan status pesanan, mendapatkan promo eksklusif, dan menyimpan riwayat transaksi.'
                    ],
                    [
                        'id' => 'bantuan',
                        'q' => 'Ke mana saya harus menghubungi jika ada kendala?',
                        'a' => 'Tim Customer Service kami siap membantu Anda 24/7. Anda bisa menghubungi kami melalui WhatsApp di tombol kontak yang tersedia atau mengirim email ke support@gamemarket.id.'
                    ]
                ];
            @endphp

            @foreach($faqs as $faq)
                <div class="faq-item" id="{{ $faq['id'] }}">
                    <div class="faq-question">
                        <span>{{ $faq['q'] }}</span>
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="faq-answer">
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questions = document.querySelectorAll('.faq-question');
        questions.forEach(q => {
            q.addEventListener('click', function() {
                const parent = this.parentElement;
                
                // Close others
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (item !== parent) {
                        item.classList.remove('active');
                    }
                });
                
                // Toggle current
                parent.classList.toggle('active');
            });
        });
        
        // Open specific FAQ if hash in URL
        if(window.location.hash) {
            const el = document.querySelector(window.location.hash);
            if(el) {
                el.classList.add('active');
                setTimeout(() => el.scrollIntoView({behavior: 'smooth', block: 'center'}), 500);
            }
        }
    });
</script>
@endpush
@endsection
