<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'GameMarket Indonesia — Marketplace toko game dan item digital terpercaya. Belanja item game dengan mudah, cepat, dan praktis menggunakan QRIS.')">
    <meta property="og:title" content="@yield('title', 'GameMarket Indonesia')">
    <meta property="og:description" content="@yield('meta_description', 'Marketplace toko game dan item digital terpercaya.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <title>@yield('title', 'GameMarket Indonesia') — Toko Game & Item Digital</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Toast Container --}}
    <div class="toast-container" id="toast-container"></div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast(@json(session('success')), 'success');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast(@json(session('error')), 'error');
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
