<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GameMarket Indonesia') }} - Autentikasi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background: var(--bg-primary);
                color: var(--text-primary);
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }
            .auth-bg {
                position: absolute;
                inset: 0;
                background: radial-gradient(ellipse at 50% 0%, rgba(0, 229, 212, 0.1) 0%, transparent 70%),
                            radial-gradient(ellipse at 0% 100%, rgba(122, 44, 255, 0.08) 0%, transparent 50%);
                z-index: -2;
            }
            .auth-grid {
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(rgba(0, 229, 212, 0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(0, 229, 212, 0.03) 1px, transparent 1px);
                background-size: 40px 40px;
                z-index: -1;
                mask-image: radial-gradient(ellipse at center, black 40%, transparent 80%);
                -webkit-mask-image: radial-gradient(ellipse at center, black 40%, transparent 80%);
            }
            .auth-card {
                background: rgba(18, 34, 53, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid var(--border-color);
                border-radius: var(--radius-xl);
                padding: var(--space-3xl) var(--space-2xl);
                width: 100%;
                max-width: 420px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(0,229,212,0.1);
                animation: fadeInUp 0.6s ease;
            }
            .auth-logo {
                display: flex;
                justify-content: center;
                margin-bottom: var(--space-2xl);
            }
            .auth-logo a {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                font-family: var(--font-heading, 'Inter');
                font-size: 1.5rem;
                font-weight: 800;
                color: var(--text-primary);
            }
            .auth-logo-icon {
                width: 40px;
                height: 40px;
                background: var(--gradient-primary);
                border-radius: var(--radius-sm);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--bg-primary);
                font-size: 1.2rem;
            }
            
            /* Form elements */
            .form-group { margin-bottom: var(--space-lg); }
            .form-label {
                display: block;
                color: var(--text-secondary);
                font-size: 0.85rem;
                margin-bottom: 6px;
                font-weight: 500;
            }
            .form-input {
                width: 100%;
                padding: 10px 14px;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid var(--border-color);
                border-radius: var(--radius-md);
                color: var(--text-primary);
                font-size: 0.95rem;
                transition: all 0.3s;
            }
            .form-input:focus {
                outline: none;
                border-color: var(--primary-cyan);
                box-shadow: 0 0 0 3px rgba(0, 229, 212, 0.1);
            }
            .btn-primary {
                width: 100%;
                padding: 12px;
                background: var(--gradient-primary);
                color: var(--bg-primary);
                border: none;
                border-radius: var(--radius-md);
                font-weight: 600;
                font-size: 1rem;
                cursor: pointer;
                transition: opacity 0.3s;
                margin-top: var(--space-md);
            }
            .btn-primary:hover { opacity: 0.9; }
            .auth-links {
                margin-top: var(--space-xl);
                text-align: center;
                font-size: 0.85rem;
                color: var(--text-secondary);
            }
            .auth-links a {
                color: var(--primary-cyan);
                text-decoration: none;
                font-weight: 600;
            }
            .auth-links a:hover { text-decoration: underline; }
            
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <div class="auth-bg"></div>
        <div class="auth-grid"></div>
        
        <div class="auth-card">
            <div class="auth-logo">
                <a href="/">
                    <div class="auth-logo-icon">GM</div>
                    GameMarket
                </a>
            </div>
            
            {{ $slot }}
        </div>
    </body>
</html>
