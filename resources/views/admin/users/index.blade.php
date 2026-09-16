@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Pengguna</h1>
</div>

<div class="admin-card mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="form-group flex-1 mb-0 min-w-[200px]">
            <label for="search" class="form-label">Cari</label>
            <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama, email, atau telepon...">
        </div>
        <div class="form-group w-48 mb-0">
            <label for="role" class="form-label">Peran</label>
            <select id="role" name="role" class="form-control">
                <option value="">Semua Peran</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        <button type="submit" class="btn-admin btn-primary h-[42px]">
            <i data-lucide="search" class="w-4 h-4 mr-2 inline-block"></i> Filter
        </button>
    </form>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="p-4 font-semibold text-gray-700">Nama</th>
                    <th class="p-4 font-semibold text-gray-700">Email</th>
                    <th class="p-4 font-semibold text-gray-700">No. Telepon</th>
                    <th class="p-4 font-semibold text-gray-700">Peran</th>
                    <th class="p-4 font-semibold text-gray-700">Terdaftar</th>
                    <th class="p-4 font-semibold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="p-4 font-medium">{{ $user->name }}</td>
                    <td class="p-4">{{ $user->email }}</td>
                    <td class="p-4 text-gray-500">{{ $user->phone ?? '-' }}</td>
                    <td class="p-4">
                        <span class="badge {{ $user->role == 'admin' ? 'badge-active' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-500 text-sm">{{ $user->created_at->format('d M Y H:i') }}</td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-admin bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 text-sm">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">
                        <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                        <p>Belum ada data pengguna.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($users) && $users->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
