@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2 w-max">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Pengguna
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-1">
        <div class="admin-card">
            <div class="text-center pb-6 border-b border-gray-100 mb-6">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center text-3xl text-gray-500 font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
                <div class="mt-3">
                    <span class="badge {{ $user->role == 'admin' ? 'badge-active' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">No. Telepon</h3>
                    <p>{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">Terdaftar Sejak</h3>
                    <p>{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-span-1 md:col-span-2">
        <div class="admin-card">
            <h2 class="text-lg font-bold mb-4">Riwayat Pesanan</h2>
            
            <div class="admin-table-container">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-4 font-semibold text-gray-700">No. Order</th>
                            <th class="p-4 font-semibold text-gray-700">Tanggal</th>
                            <th class="p-4 font-semibold text-gray-700">Total</th>
                            <th class="p-4 font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders ?? [] as $order)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="p-4 font-mono text-sm">{{ $order->order_number }}</td>
                            <td class="p-4 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="p-4 font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="badge {{ $order->status == 'completed' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">
                                <i data-lucide="shopping-cart" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                                <p>Belum ada riwayat pesanan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
