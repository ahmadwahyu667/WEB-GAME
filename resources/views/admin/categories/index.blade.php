@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Kategori</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn-admin btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> Tambah Kategori
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="p-4 font-semibold text-gray-700">Nama</th>
                    <th class="p-4 font-semibold text-gray-700">Slug</th>
                    <th class="p-4 font-semibold text-gray-700 text-center">Jumlah Produk</th>
                    <th class="p-4 font-semibold text-gray-700">Status</th>
                    <th class="p-4 font-semibold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="p-4 font-medium">{{ $category->name }}</td>
                    <td class="p-4 text-gray-500">{{ $category->slug }}</td>
                    <td class="p-4 text-center">{{ $category->products_count ?? 0 }}</td>
                    <td class="p-4">
                        <span class="badge {{ $category->status == 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-admin btn-secondary py-1 px-2 text-sm">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-admin btn-danger py-1 px-2 text-sm">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                        <p>Belum ada data kategori.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($categories) && $categories->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
