@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')

<div class="flex-between" style="margin-bottom: var(--space-xl);">
    <h2 style="font-weight: 700; color: var(--text-primary); font-size: 1.1rem;">Daftar Produk</h2>
    <a href="{{ route('admin.products.create') }}" class="btn-admin btn-admin-primary">
        <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Tambah Produk
    </a>
</div>

{{-- Filter --}}
<div class="admin-card" style="margin-bottom: var(--space-xl); padding: var(--space-lg);">
    <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: var(--space-md); align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label class="form-label">Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nama produk...">
        </div>
        <div class="form-group" style="min-width: 150px; margin-bottom: 0;">
            <label class="form-label">Game</label>
            <select name="game_id" class="form-control">
                <option value="">Semua Game</option>
                @foreach($games ?? [] as $game)
                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="min-width: 150px; margin-bottom: 0;">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: var(--space-sm);">
            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 10px 20px;">Filter</button>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-secondary" style="padding: 10px 20px;">Reset</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div class="admin-table-container">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Game / Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($product->main_image && $product->main_image !== 'placeholder.png')
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                @else
                                    <div style="width: 40px; height: 40px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;">
                                        <i data-lucide="image" style="width: 16px; height: 16px; color: var(--text-muted);"></i>
                                    </div>
                                @endif
                                <span style="font-weight: 500;">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem;">{{ $product->game->name ?? '-' }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $product->category->name ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @if($product->discount_price)
                                <div style="font-size: 0.75rem; color: #10B981;">Diskon: Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge badge-cancelled">Habis</span>
                            @elseif($product->stock <= 5)
                                <span style="display: inline-block; padding: 3px 10px; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 600; background: rgba(239,68,68,0.1); color: #EF4444;">{{ $product->stock }}</span>
                            @else
                                <span style="color: var(--text-secondary);">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $product->status === 'active' ? 'active' : 'inactive' }}">{{ ucfirst($product->status) }}</span>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn-icon" title="Lihat">
                                    <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-icon edit" title="Edit">
                                    <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                                </a>
                                <form id="delete-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('delete-{{ $product->id }}')" class="btn-icon delete" title="Hapus">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: var(--space-2xl); color: var(--text-secondary);">
                            <i data-lucide="package-x" style="width: 48px; height: 48px; color: var(--text-muted); margin: 0 auto var(--space-md); display: block;"></i>
                            Belum ada produk
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div style="padding: var(--space-lg); border-top: 1px solid var(--border-color);">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
