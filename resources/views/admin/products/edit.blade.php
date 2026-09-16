@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')

<div class="flex-between" style="margin-bottom: var(--space-xl);">
    <h2 style="font-weight: 700; color: var(--text-primary); font-size: 1.1rem;">Edit Produk: {{ $product->name }}</h2>
    <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-secondary">← Kembali</a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-lg);">
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                @error('name') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-control" required>
                @error('slug') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Game *</label>
                <select name="game_id" class="form-control" required>
                    <option value="">Pilih Game</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id', $product->game_id) == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                    @endforeach
                </select>
                @error('game_id') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" required min="0">
                @error('price') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Harga Diskon (Rp)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="form-control" min="0">
                @error('discount_price') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Stok *</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required min="0">
                @error('stock') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Pengiriman *</label>
                <select name="delivery_type" class="form-control" required>
                    @foreach(['instant' => 'Instant', 'manual' => 'Manual', 'code' => 'Kode', 'account_data' => 'Data Akun', 'instruction' => 'Instruksi'] as $value => $label)
                        <option value="{{ $value }}" {{ old('delivery_type', $product->delivery_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('delivery_type') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Utama</label>
                @if($product->main_image && $product->main_image !== 'placeholder.png')
                    <div style="margin-bottom: 8px;">
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    </div>
                @endif
                <input type="file" name="main_image" class="form-control" accept="image/*">
                @error('main_image') <small style="color: #EF4444;">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-top: var(--space-md);">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description') <small style="color: #EF4444;">{{ $message }}</small> @enderror
        </div>

        <div style="display: flex; gap: var(--space-md); margin-top: var(--space-xl);">
            <button type="submit" class="btn-admin btn-admin-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
