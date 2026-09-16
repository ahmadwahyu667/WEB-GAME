@extends('layouts.admin')

@section('title', 'Manajemen Promo')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Promo</h1>
    <a href="{{ route('admin.promos.create') }}" class="btn-admin btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Promo
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="p-4 border-b">Title</th>
                    <th class="p-4 border-b">Type</th>
                    <th class="p-4 border-b">Value</th>
                    <th class="p-4 border-b">Starts At</th>
                    <th class="p-4 border-b">Ends At</th>
                    <th class="p-4 border-b">Usage Count</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promos as $promo)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $promo->title }}</td>
                    <td class="p-4">{{ ucfirst($promo->type) }}</td>
                    <td class="p-4">{{ $promo->type == 'percentage' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}</td>
                    <td class="p-4">{{ $promo->starts_at ? \Carbon\Carbon::parse($promo->starts_at)->format('d M Y H:i') : '-' }}</td>
                    <td class="p-4">{{ $promo->ends_at ? \Carbon\Carbon::parse($promo->ends_at)->format('d M Y H:i') : '-' }}</td>
                    <td class="p-4">{{ $promo->usage_count ?? 0 }}</td>
                    <td class="p-4">
                        <span class="badge {{ $promo->status == 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($promo->status) }}
                        </span>
                    </td>
                    <td class="p-4 flex space-x-2">
                        <a href="{{ route('admin.promos.edit', $promo->id) }}" class="btn-admin btn-secondary text-sm">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                    <td colspan="8" class="p-4 text-center text-gray-500">Tidak ada data promo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
