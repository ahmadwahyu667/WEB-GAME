@extends('layouts.app')

@section('content')
<section class="section" style="padding-top: 120px; min-height: 80vh;">
    <div class="container">
        @include('components.breadcrumb', ['crumbs' => [['label' => 'Games', 'url' => url('/games')]]])
        
        <div class="section-title text-left" style="text-align: left; margin-bottom: 32px;">
            <h2>Semua <span class="accent">Game</span></h2>
            <p>Pilih game favoritmu dan temukan produk terbaik.</p>
        </div>
        
        <div class="product-grid" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
            @forelse($games as $game)
                <a href="{{ url('/games/' . $game->slug) }}" class="game-card">
                    @if($game->icon_path)
                        <img src="{{ asset('storage/' . $game->icon_path) }}" alt="{{ $game->name }}" class="game-card__logo">
                    @else
                        <div class="game-card__logo" style="background: var(--gradient-primary); display:flex; align-items:center; justify-content:center; color:white; font-size:24px; font-weight:bold;">
                            {{ substr($game->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="game-card__name">{{ $game->name }}</h3>
                    <p class="game-card__count">{{ $game->products_count }} Produk</p>
                </a>
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">
                    <div class="empty-state__icon">🎮</div>
                    <h3 class="empty-state__title">Belum ada game</h3>
                    <p class="empty-state__message">Daftar game akan segera hadir.</p>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 40px;">
            {{ $games->links() }}
        </div>
    </div>
</section>
@endsection
