<x-guest-layout>
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Daftar Akun Baru</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Bergabunglah dan nikmati kemudahan transaksi.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama kamu" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" style="color: #EF4444; font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan email aktif" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #EF4444; font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <!-- WhatsApp -->
        <div class="form-group">
            <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
            <input id="whatsapp" class="form-input" type="text" name="whatsapp" value="{{ old('whatsapp') }}" autocomplete="tel" placeholder="Contoh: 08123456789" />
            <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" style="color: #EF4444; font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #EF4444; font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group" style="margin-bottom: var(--space-xl);">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: #EF4444; font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <div>
            <button type="submit" class="btn-primary">
                Daftar Sekarang
            </button>
        </div>
        
        <div class="auth-links">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>
