@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Berita</h1>
    <a href="{{ route('admin.news.create') }}" class="btn-admin btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Berita
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="p-4 border-b">Title</th>
                    <th class="p-4 border-b">Author</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b">Published At</th>
                    <th class="p-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $item->title }}</td>
                    <td class="p-4">{{ $item->author ?? '-' }}</td>
                    <td class="p-4">
                        <span class="badge {{ $item->status == 'published' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="p-4">{{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : '-' }}</td>
                    <td class="p-4 flex space-x-2">
                        <a href="{{ route('admin.news.edit', $item->id) }}" class="btn-admin btn-secondary text-sm">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin btn-danger text-sm">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data berita.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
