@extends('layouts.admin')

@section('title', 'Manajemen Banner')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Banner</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn-admin btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Banner
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="p-4 border-b">Image</th>
                    <th class="p-4 border-b">Title</th>
                    <th class="p-4 border-b">Sort Order</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">
                        @if($banner->image)
                            <img src="{{ Storage::url($banner->image) }}" alt="Banner" class="h-16 rounded object-cover">
                        @else
                            <span class="text-gray-400">No Image</span>
                        @endif
                    </td>
                    <td class="p-4">{{ $banner->title }}</td>
                    <td class="p-4">{{ $banner->sort_order }}</td>
                    <td class="p-4">
                        <span class="badge {{ $banner->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="p-4 flex space-x-2">
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn-admin btn-secondary text-sm">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data banner.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
