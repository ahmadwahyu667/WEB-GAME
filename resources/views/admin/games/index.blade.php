@extends('layouts.admin')

@section('title', 'Manajemen Game')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Game</h1>
    <a href="{{ route('admin.games.create') }}" class="btn-admin btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-2 inline-block"></i> Tambah Game
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
                @forelse($games as $game)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="p-4 font-medium">{{ $game->name }}</td>
                    <td class="p-4 text-gray-500">{{ $game->slug }}</td>
                    <td class="p-4 text-center">{{ $game->products_count ?? 0 }}</td>
                    <td class="p-4">
                        <span class="badge {{ $game->status == 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($game->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.games.edit', $game->id) }}" class="btn-admin btn-secondary py-1 px-2 text-sm">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                        <p>Belum ada data game.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($games) && $games->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $games->links() }}
    </div>
    @endif
</div>
@endsection
