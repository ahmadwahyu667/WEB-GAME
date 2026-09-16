@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="section container">
    <div style="max-width: 800px; margin: 0 auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
            <h1 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 800; color: var(--text-primary); mragin: 0;">Notifikasi</h1>
            
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.read_all') }}" method="POST">
                @csrf
                <button type="submit" style="padding: 8px 16px; background: rgba(0, 229, 212, 0.1); color: var(--primary-cyan); border: 1px solid rgba(0, 229, 212, 0.2); border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    Tandai semua dibaca
                </button>
            </form>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                @foreach($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $type = $notification->data['type'] ?? 'info';
                        
                        $iconColors = [
                            'success' => '#10B981',
                            'warning' => '#F59E0B',
                            'error' => '#EF4444',
                            'info' => '#3B82F6',
                            'order' => '#7A2CFF',
                            'promo' => '#00E5D4'
                        ];
                        
                        $color = $iconColors[$type] ?? '#3B82F6';
                        
                        // Icons
                        $iconSvg = '';
                        if(in_array($type, ['order', 'success'])) {
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        } elseif ($type === 'warning' || $type === 'error') {
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        } else {
                            // Default bell
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>';
                        }
                    @endphp

                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: var(--space-lg); display: flex; gap: var(--space-md); align-items: flex-start; {{ $isUnread ? 'border-left: 4px solid var(--primary-cyan); background: rgba(0, 229, 212, 0.03);' : '' }}">
                        
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ $color }}20; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="24" height="24" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24">
                                {!! $iconSvg !!}
                            </svg>
                        </div>
                        
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 600; color: {{ $isUnread ? 'var(--text-primary)' : 'var(--text-secondary)' }}; margin: 0;">
                                    {{ $notification->data['title'] ?? 'Pemberitahuan' }}
                                </h3>
                                <span style="font-size: 0.75rem; color: var(--text-muted); white-space: nowrap; margin-left: 12px;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5; margin-bottom: {{ $isUnread ? 'var(--space-md)' : '0' }};">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            
                            @if($isUnread)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; padding: 0; color: var(--primary-cyan); font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                        Tandai sudah dibaca
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <div style="margin-top: var(--space-2xl);">
                {{ $notifications->links('pagination::tailwind') }}
            </div>
        @else
            <div style="text-align: center; padding: var(--space-3xl) 0; background: var(--bg-card); border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <div style="color: var(--text-muted); font-size: 3rem; margin-bottom: var(--space-md);">
                    <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Tidak ada notifikasi</h3>
                <p style="color: var(--text-secondary);">Anda belum menerima pemberitahuan apa pun.</p>
            </div>
        @endif
        
    </div>
</div>
@endsection
