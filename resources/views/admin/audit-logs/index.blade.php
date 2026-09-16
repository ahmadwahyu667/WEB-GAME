@extends('layouts.admin')

@section('title', 'Log Aktivitas (Audit Logs)')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Log Aktivitas</h1>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="p-4 font-semibold text-gray-700">Waktu</th>
                    <th class="p-4 font-semibold text-gray-700">Admin</th>
                    <th class="p-4 font-semibold text-gray-700">Aksi</th>
                    <th class="p-4 font-semibold text-gray-700">Target Tipe</th>
                    <th class="p-4 font-semibold text-gray-700">Target ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="p-4 text-gray-500 text-sm whitespace-nowrap">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    <td class="p-4 font-medium">{{ $log->user->name ?? 'Sistem' }}</td>
                    <td class="p-4">
                        <span class="badge bg-gray-100 text-gray-800 border border-gray-200">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-600">{{ $log->target_type }}</td>
                    <td class="p-4 font-mono text-sm">{{ $log->target_id }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        <i data-lucide="history" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                        <p>Belum ada log aktivitas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($logs) && $logs->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
