@extends('layouts.admin')

@section('title', 'Tambah Banner')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Tambah Banner</h1>
    <a href="{{ route('admin.banners.index') }}" class="btn-admin btn-secondary">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
    </a>
</div>

<div class="admin-card p-6">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group mb-4">
                <label class="form-label" for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="subtitle">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle') }}">
            </div>
            
            <div class="form-group mb-4 md:col-span-2">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="cta_text">CTA Text</label>
                <input type="text" name="cta_text" id="cta_text" class="form-control" value="{{ old('cta_text') }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="cta_url">CTA URL</label>
                <input type="url" name="cta_url" id="cta_url" class="form-control" value="{{ old('cta_url') }}">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" for="is_active">Status</label>
                <select name="is_active" id="is_active" class="form-control" required>
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', '0') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="btn-admin btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
