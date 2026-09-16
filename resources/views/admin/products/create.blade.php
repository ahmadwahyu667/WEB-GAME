@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')

<div class="flex-between" style="margin-bottom: var(--space-xl);">
    <h2 style="font-weight: 700; color: var(--text-primary); font-size: 1.1rem;">Tambah Produk Baru</h2>
    <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-secondary">← Kembali</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-lg);">
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required placeholder="Contoh: Arcana Phantom Assassin">
                @error('name') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" required placeholder="arcana-phantom-assassin">
                @error('slug') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Game *</label>
                <select name="game_id" class="form-control" required>
                    <option value="">Pilih Game</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                    @endforeach
                </select>
                @error('game_id') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="price" value="{{ old('price') }}" class="form-control" required min="0" placeholder="50000">
                @error('price') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Harga Diskon (Rp)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price') }}" class="form-control" min="0" placeholder="Kosongkan jika tidak ada diskon">
                @error('discount_price') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Stok *</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-control" required min="0">
                @error('stock') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Pengiriman *</label>
                <select name="delivery_type" class="form-control" required>
                    <option value="instant" {{ old('delivery_type') == 'instant' ? 'selected' : '' }}>Instant</option>
                    <option value="manual" {{ old('delivery_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="code" {{ old('delivery_type') == 'code' ? 'selected' : '' }}>Kode</option>
                    <option value="account_data" {{ old('delivery_type') == 'account_data' ? 'selected' : '' }}>Data Akun</option>
                    <option value="instruction" {{ old('delivery_type') == 'instruction' ? 'selected' : '' }}>Instruksi</option>
                </select>
                @error('delivery_type') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Utama</label>
                <input type="file" name="main_image" class="form-control" accept="image/*">
                @error('main_image') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-top: var(--space-md);">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
            @error('description') <small style="color: #EF4444;">{{ $message }}</small> @enderror
        </div>

        <div style="display: flex; gap: var(--space-md); margin-top: var(--space-xl);">
            <button type="submit" class="btn-admin btn-admin-primary">Simpan Produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
