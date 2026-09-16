@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Edit Berita</h1>
    <a href="{{ route('admin.news.index') }}" class="btn-admin btn-secondary">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
    </a>
</div>

<div class="admin-card p-6">
    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $news->title) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $news->slug) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="thumbnail">Thumbnail (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                @if($news->thumbnail)
                    <div class="mt-2">
                        <img src="{{ Storage::url($news->thumbnail) }}" alt="Preview" class="h-20 rounded">
                    </div>
                @endif
            </div>
            
            <div class="form-group">
                <label class="form-label" for="excerpt">Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="2" class="form-control">{{ old('excerpt', $news->excerpt) }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="content">Content</label>
                <textarea name="content" id="content" rows="6" class="form-control" required>{{ old('content', $news->content) }}</textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group">
                    <label class="form-label" for="author">Author</label>
                    <input type="text" name="author" id="author" class="form-control" value="{{ old('author', $news->author) }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="published_at">Published At</label>
                    <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d\TH:i') : '') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="btn-admin btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
