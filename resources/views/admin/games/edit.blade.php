@extends('layouts.admin')

@section('title', 'Edit Game')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.games.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2 w-max">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Game
    </a>
</div>

<div class="admin-card max-w-3xl">
    <h2 class="text-xl font-bold mb-6">Edit Game: {{ $game->name }}</h2>
    
    <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-4">
            <label for="name" class="form-label">Nama Game <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $game->name) }}" required>
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mb-4">
            <label for="slug" class="form-label">Slug <span class="text-red-500">*</span></label>
            <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $game->slug) }}" required>
            @error('slug') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mb-4">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $game->description) }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mb-6">
            <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
            <select id="status" name="status" class="form-control" required>
                <option value="active" {{ old('status', $game->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $game->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('admin.games.index') }}" class="btn-admin bg-gray-200 hover:bg-gray-300 text-gray-800">Batal</a>
            <button type="submit" class="btn-admin btn-primary">
                <i data-lucide="save" class="w-4 h-4 mr-2 inline-block"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if(nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                let slug = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            });
        }
    });
</script>
@endsection
