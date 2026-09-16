<x-guest-layout>
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Masuk ke Akun Kamu</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Selamat datang kembali, Gamer!</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" style="color: var(--primary-cyan); font-size: 0.9rem; text-align: center; margin-bottom: 1rem;" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email kamu" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #EF4444; font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <!-- Password -->
        <div class="form-group" style="margin-bottom: var(--space-sm);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: var(--text-secondary); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--primary-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password kamu" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #EF4444; font-size: 0.8rem; margin-top: 4px;" />
        </div>

        <!-- Remember Me -->
        <div class="form-group" style="display: flex; align-items: center; margin-bottom: var(--space-xl);">
            <label for="remember_me" style="display: inline-flex; align-items: center; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember" style="accent-color: var(--primary-cyan); width: 16px; height: 16px; cursor: pointer;">
                <span style="margin-left: 8px; font-size: 0.85rem; color: var(--text-secondary);">Ingat Saya</span>
            </label>
        </div>

        <div>
            <button type="submit" class="btn-primary">
                Masuk Sekarang
            </button>
        </div>
        
        <div class="auth-links">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </form>
</x-guest-layout>
