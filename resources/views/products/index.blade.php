@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container section">
    {{-- Breadcrumb --}}
    <nav class="breadcrumb" style="margin-bottom: var(--space-xl); color: var(--text-secondary); font-size: 0.85rem;">
        <a href="{{ route('home') }}">Home</a> &gt; <span>Produk</span>
    </nav>

    @php
        $games = \App\Models\Game::where('is_active', true)->get();
        $categories = \App\Models\Category::all();
        
        $currentSort = request('sort', 'terbaru');
        $q = request('q');
    @endphp

    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-2xl); flex-wrap: wrap; gap: var(--space-md);">
        <h1 style="font-family: var(--font-heading); font-size: 2rem;">Katalog Produk</h1>
        
        <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
            @if(request('game')) <input type="hidden" name="game" value="{{ request('game') }}"> @endif
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
            @if(request('in_stock')) <input type="hidden" name="in_stock" value="{{ request('in_stock') }}"> @endif
            @if(request('promo')) <input type="hidden" name="promo" value="{{ request('promo') }}"> @endif
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

            <input type="text" name="q" value="{{ $q }}" class="form-input" placeholder="Cari produk..." style="border-radius: var(--radius-full); width: 100%;">
            <button type="submit" class="btn btn--primary" style="border-radius: var(--radius-full);">Cari</button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: var(--space-2xl); align-items: start;">
        
        {{-- Sidebar Filter --}}
        <aside class="filter-sidebar card" style="padding: var(--space-lg); position: sticky; top: 100px;">
            <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                <input type="hidden" name="q" value="{{ $q }}">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-md);">
                    <h3 style="font-family: var(--font-heading);">Filter</h3>
                    <button type="submit" class="btn btn--sm btn--primary">Terapkan</button>
                </div>

                <div class="filter-group" style="margin-bottom: var(--space-lg);">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Urutkan</label>
                    <select name="sort" class="form-input" onchange="this.form.submit()">
                        <option value="terbaru" {{ $currentSort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlaris" {{ $currentSort == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                        <option value="harga_terendah" {{ $currentSort == 'harga_terendah' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_tertinggi" {{ $currentSort == 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rating" {{ $currentSort == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </div>

                <div class="filter-group" style="margin-bottom: var(--space-lg);">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Game</label>
                    @foreach($games as $game)
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 0.9rem;">
                            <input type="radio" name="game" value="{{ $game->slug }}" {{ request('game') == $game->slug ? 'checked' : '' }} onchange="this.form.submit()">
                            {{ $game->name }}
                        </label>
                    @endforeach
                    @if(request('game'))
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 0.9rem; color: var(--primary-cyan);">
                            <input type="radio" name="game" value="" onchange="this.form.submit()">
                            Semua Game
                        </label>
                    @endif
                </div>

                <div class="filter-group" style="margin-bottom: var(--space-lg);">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Kategori</label>
                    @foreach($categories as $category)
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 0.9rem;">
                            <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }} onchange="this.form.submit()">
                            {{ $category->name }}
                        </label>
                    @endforeach
                    @if(request('category'))
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 0.9rem; color: var(--primary-cyan);">
                            <input type="radio" name="category" value="" onchange="this.form.submit()">
                            Semua Kategori
                        </label>
                    @endif
                </div>

                <div class="filter-group" style="margin-bottom: var(--space-lg);">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Harga (Rp)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="number" name="min_price" class="form-input" placeholder="Min" value="{{ request('min_price') }}">
                        <span>-</span>
                        <input type="number" name="max_price" class="form-input" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                </div>

                <div class="filter-group" style="margin-bottom: var(--space-lg);">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem;">
                        <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                        Hanya Produk Tersedia
                    </label>
                </div>

                <div class="filter-group">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem;">
                        <input type="checkbox" name="promo" value="1" {{ request('promo') ? 'checked' : '' }} onchange="this.form.submit()">
                        Sedang Promo
                    </label>
                </div>
            </form>
        </aside>

        {{-- Product Grid --}}
        <div>
            @if($products->isEmpty())
                <div style="text-align: center; padding: var(--space-3xl) 0; color: var(--text-secondary);">
                    <div style="font-size: 4rem; margin-bottom: var(--space-md);">😢</div>
                    <h3 style="font-family: var(--font-heading); color: var(--text-primary);">Produk tidak ditemukan</h3>
                    <p>Coba sesuaikan filter atau pencarian Anda.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: var(--space-lg);">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
                
                <div style="margin-top: var(--space-2xl);">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .page-header { flex-direction: column; align-items: stretch; }
    .page-header form { max-width: 100%; }
    div[style*="grid-template-columns: 280px"] { grid-template-columns: 1fr !important; }
    .filter-sidebar { position: static !important; margin-bottom: var(--space-lg); }
}
</style>
@endsection
